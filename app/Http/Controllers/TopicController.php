<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Topic;
use App\Models\TopicVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        // Domain Logic
        if ($request->has('domain')) {
            session(['current_domain' => $request->domain]);
        }
        $currentDomainSlug = session('current_domain', 'flutter');
        $currentDomain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();

        $query = Topic::where('domain_id', $currentDomain->id)->with('tags');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('tag')) {
            $tagSlug = $request->tag;
            $query->whereHas('tags', fn($q) => $q->where('slug', $tagSlug));
        }

        // Pinned first, then latest
        $topics = $query->orderByDesc('is_pinned')->latest()->get();

        $categories = Topic::where('domain_id', $currentDomain->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');
            
        $tags = Tag::whereHas('topics', function($q) use ($currentDomain) {
            $q->where('domain_id', $currentDomain->id);
        })->withCount(['topics' => fn($q) => $q->where('domain_id', $currentDomain->id)])
          ->orderByDesc('topics_count')
          ->get();

        // Topics needing review (not reviewed in 7+ days)
        $reviewCount = Topic::where('domain_id', $currentDomain->id)->needsReview(7)->count();
        
        $domains = \App\Models\Domain::all();

        return view('topics.index', compact('topics', 'categories', 'tags', 'reviewCount', 'currentDomain', 'domains'));
    }

    public function create(Request $request)
    {
        if ($request->has('domain')) {
            session(['current_domain' => $request->domain]);
        }
        
        $title = $request->query('title', '');
        
        $currentDomainSlug = session('current_domain', 'flutter');
        $domain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();
        $defaultLanguage = $domain->default_language;

        $tags = Tag::whereHas('topics', fn($q) => $q->where('domain_id', $domain->id))
            ->withCount(['topics' => fn($q) => $q->where('domain_id', $domain->id)])
            ->orderByDesc('topics_count')
            ->limit(20)
            ->get();
            
        $categories = Topic::where('domain_id', $domain->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('topics.create', compact('title', 'tags', 'categories', 'domain', 'defaultLanguage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
        ]);

        $currentDomainSlug = session('current_domain', 'flutter');
        $domain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();

        // Check for duplicate title within domain
        $exists = Topic::where('title', $validated['title'])
            ->where('domain_id', $domain->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['title' => 'يوجد موضوع بهذا العنوان في هذا الدومين بالفعل.']);
        }

        $topic = Topic::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category' => $validated['category'] ?? null,
            'domain_id' => $domain->id,
        ]);

        // Handle tags
        $this->syncTags($topic, $request->input('tags', ''));

        // Save initial version
        $topic->saveVersion('إنشاء الموضوع');

        return redirect()->route('topics.show', $topic->slug)
            ->with('success', 'تم إنشاء الموضوع بنجاح!');
    }

    public function show(Topic $topic)
    {
        // Switch domain context if viewing a topic from another domain
        if ($topic->domain_id && $topic->domain->slug !== session('current_domain', 'flutter')) {
            session(['current_domain' => $topic->domain->slug]);
        }

        $backlinks = $topic->backlinks;
        $topic->markReviewed();
        return view('topics.show', compact('topic', 'backlinks'));
    }

    public function edit(Topic $topic)
    {
        // We should ensure session matches topic domain?
        if ($topic->domain_id) {
             session(['current_domain' => $topic->domain->slug]);
        }

        $currentDomainSlug = session('current_domain', 'flutter');
        $currentDomain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();
        $defaultLanguage = $currentDomain->default_language;

        $tags = Tag::whereHas('topics', fn($q) => $q->where('domain_id', $currentDomain->id))
            ->withCount(['topics' => fn($q) => $q->where('domain_id', $currentDomain->id)])
            ->orderByDesc('topics_count')
            ->limit(20)
            ->get();
            
        $categories = Topic::where('domain_id', $currentDomain->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $topicTags = $topic->tags->pluck('name')->implode(', ');
        return view('topics.edit', compact('topic', 'tags', 'topicTags', 'categories', 'currentDomain', 'defaultLanguage'));
    }




    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'title' => [
                'required', 'string', 'max:255',
                \Illuminate\Validation\Rule::unique('topics')->ignore($topic->id)->where(function ($query) use ($topic) {
                    return $query->where('domain_id', $topic->domain_id);
                }),
            ],
            'body' => 'required|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
        ]);

        // Save version before update
        $topic->saveVersion($request->input('change_note', 'تحديث'));

        $topic->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category' => $validated['category'] ?? null,
        ]);

        $this->syncTags($topic, $request->input('tags', ''));

        return redirect()->route('topics.show', $topic->slug)
            ->with('success', 'تم تحديث الموضوع بنجاح!');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('topics.index')
            ->with('success', 'تم حذف الموضوع بنجاح!');
    }

    public function search(Request $request)
    {
        $currentDomainSlug = session('current_domain', 'flutter');
        $currentDomain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();

        $query = $request->input('q', '');
        $results = collect();

        if ($query) {
            $results = Topic::where('domain_id', $currentDomain->id)
                ->search($query)
                ->with('tags')
                ->get();
        }

        return view('search', compact('results', 'query'));
    }

    // ─── New Features ───

    public function togglePin(Topic $topic)
    {
        $topic->update(['is_pinned' => !$topic->is_pinned]);
        $msg = $topic->is_pinned ? 'تم تثبيت الموضوع ⭐' : 'تم إلغاء التثبيت';
        return back()->with('success', $msg);
    }

    public function versions(Topic $topic)
    {
        $versions = $topic->versions;
        return view('topics.versions', compact('topic', 'versions'));
    }

    public function restoreVersion(Topic $topic, TopicVersion $version)
    {
        $topic->saveVersion('قبل استعادة النسخة ' . $version->version_number);

        $topic->update([
            'title' => $version->title,
            'body' => $version->body,
        ]);

        return redirect()->route('topics.show', $topic->slug)
            ->with('success', 'تم استعادة النسخة رقم ' . $version->version_number);
    }

    public function review()
    {
        $topics = Topic::needsReview(7)->with('tags')->latest('last_reviewed_at')->get();
        return view('topics.review', compact('topics'));
    }

    public function export(Topic $topic, string $format)
    {
        if ($format === 'markdown') {
            $content = "# {$topic->title}\n\n";
            if ($topic->category) {
                $content .= "**التصنيف:** {$topic->category}\n\n";
            }
            if ($topic->tags->count()) {
                $content .= "**Tags:** " . $topic->tags->pluck('name')->implode(', ') . "\n\n";
            }
            $content .= "---\n\n";
            $content .= $topic->body;

            return response($content)
                ->header('Content-Type', 'text/markdown')
                ->header('Content-Disposition', 'attachment; filename="' . $topic->slug . '.md"');
        }

        abort(404);
    }

    public function graph()
    {
        $currentDomainSlug = session('current_domain', 'flutter');
        $currentDomain = \App\Models\Domain::where('slug', $currentDomainSlug)->firstOrFail();

        $topics = Topic::where('domain_id', $currentDomain->id)->get(['id', 'title', 'slug', 'category', 'body']);
        $nodes = [];
        $links = [];

        foreach ($topics as $topic) {
            $nodes[] = [
                'id' => $topic->id,
                'name' => $topic->title,
                'slug' => $topic->slug,
                'category' => $topic->category ?? 'غير مصنف',
            ];

            // Find wiki links in body
            preg_match_all('/\[\[([^\]]+)\]\]/', $topic->body, $matches);
            foreach ($matches[1] as $linkedTitle) {
                $linkedSlug = Str::slug($linkedTitle);
                $linkedTopic = $topics->firstWhere('slug', $linkedSlug);
                if ($linkedTopic) {
                    $links[] = [
                        'source' => $topic->id,
                        'target' => $linkedTopic->id,
                    ];
                }
            }
        }

        return view('topics.graph', compact('nodes', 'links'));
    }

    public function apiTopics(Request $request)
    {
        $currentDomainSlug = session('current_domain', 'flutter');
        $currDomainId = \App\Models\Domain::where('slug', $currentDomainSlug)->value('id');

        $q = $request->input('q', '');
        $topics = Topic::where('domain_id', $currDomainId)
            ->where('title', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get(['id', 'title', 'slug']);
        return response()->json($topics);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            return response()->json(['url' => asset('storage/' . $path)]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }

    // ─── Helpers ───

    private function syncTags(Topic $topic, string $tagsString): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            if (empty($name)) continue;
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'color' => $this->randomTagColor()]
            );
            $tagIds[] = $tag->id;
        }

        $topic->tags()->sync($tagIds);
    }

    private function randomTagColor(): string
    {
        $colors = ['#06b6d4', '#4ade80', '#f59e0b', '#ef4444', '#3b82f6', '#f97316', '#ec4899', '#84cc16'];
        return $colors[array_rand($colors)];
    }
}
