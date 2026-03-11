<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'domains' => Domain::count(),
            'topics' => Topic::count(),
            'tags' => Tag::count(),
            'categories' => Topic::whereNotNull('category')->distinct()->count('category'),
            'reviews_pending' => Topic::needsReview(7)->count(),
        ];

        return view('admin.index', compact('stats'));
    }

    public function domains()
    {
        $domains = Domain::withCount('topics')->get();
        return view('admin.domains.index', compact('domains'));
    }

    public function createDomain()
    {
        return view('admin.domains.create');
    }

    public function storeDomain(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|alpha_dash|unique:domains',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|max:20',
            'default_language' => 'nullable|string|max:20',
        ]);

        if (empty($validated['default_language'])) {
            $validated['default_language'] = 'plaintext';
        }

        Domain::create($validated);

        return redirect()->route('admin.domains.index')->with('success', 'تم إضافة الدومين بنجاح');
    }

    public function editDomain(Domain $domain)
    {
        return view('admin.domains.edit', compact('domain'));
    }

    public function updateDomain(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|alpha_dash|unique:domains,slug,' . $domain->id,
            'icon' => 'required|string|max:10',
            'color' => 'required|string|max:20',
            'default_language' => 'nullable|string|max:20',
        ]);

        if (empty($validated['default_language'])) {
            $validated['default_language'] = 'plaintext';
        }

        $domain->update($validated);

        return redirect()->route('admin.domains.index')->with('success', 'تم العثور على الدومين بنجاح');
    }

    public function destroyDomain(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('admin.domains.index')->with('success', 'تم حذف الدومين بنجاح');
    }

    public function tags(Request $request)
    {
        $domainId = $request->input('domain');
        
        $query = Tag::query();

        if ($domainId) {
            $query->whereHas('topics', function($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            });
            $query->withCount(['topics' => function($q) use ($domainId) {
                $q->where('domain_id', $domainId);
            }]);
        } else {
            $query->withCount('topics');
        }

        $tags = $query->orderByDesc('topics_count')->get();
        $domains = Domain::all();
        
        return view('admin.tags.index', compact('tags', 'domains'));
    }

    public function editTag(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function updateTag(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|alpha_dash|unique:tags,slug,' . $tag->id,
            'color' => 'required|string|max:20',
        ]);
        $tag->update($validated);
        return redirect()->route('admin.tags.index')->with('success', 'تم تحديث التاق بنجاح');
    }

    public function destroyTag(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'تم حذف التاق بنجاح');
    }

    public function categories(Request $request)
    {
        $domainId = $request->input('domain');

        $query = Topic::whereNotNull('category');
        
        if ($domainId) {
            $query->where('domain_id', $domainId);
        }

        $categories = $query->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();
            
        $domains = Domain::all();
            
        return view('admin.categories.index', compact('categories', 'domains'));
    }

    public function renameCategory(Request $request)
    {
        $validated = $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:100',
        ]);

        Topic::where('category', $validated['old_name'])
             ->update(['category' => $validated['new_name']]);

        return redirect()->route('admin.categories.index')->with('success', 'تم إعادة تسمية التصنيف بنجاح');
    }

    public function destroyCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        Topic::where('category', $validated['name'])
             ->update(['category' => null]);

        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }
}
