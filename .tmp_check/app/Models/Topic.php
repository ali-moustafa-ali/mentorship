<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class Topic extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'category', 'is_pinned', 'view_count', 'last_reviewed_at', 'domain_id'];

    protected $casts = [
        'is_pinned' => 'boolean',
        'last_reviewed_at' => 'datetime',
    ];

    /**
     * Auto-generate slug from title whenever title is set.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                $slug = Str::slug($value);
                $originalSlug = $slug;
                $count = 1;

                while (static::where('slug', $slug)->when($this->id, fn($q) => $q->where('id', '!=', $this->id))->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                return [
                    'title' => $value,
                    'slug' => $slug,
                ];
            },
        );
    }

    // ─── Relationships ───

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function versions()
    {
        return $this->hasMany(TopicVersion::class)->orderByDesc('version_number');
    }

    // ─── Accessors ───

    /**
     * Render body: wiki links → hyperlinks, then Markdown → HTML, then code block wrappers.
     */
    public function getRenderedBodyAttribute(): string
    {
        $body = $this->body;

        // 1. Convert [[Topic Name]] to markdown links BEFORE markdown parsing
        $body = preg_replace_callback(
            '/\[\[([^\]]+)\]\]/',
            function ($matches) {
                $topicName = $matches[1];
                $slug = Str::slug($topicName);
                $exists = static::where('slug', $slug)->exists();
                if ($exists) {
                    $url = route('topics.show', $slug);
                    return '[' . $topicName . '](' . $url . '){.wiki-link}';
                } else {
                    $url = route('topics.create', ['title' => $topicName]);
                    return '[' . $topicName . '](' . $url . '){.wiki-link .wiki-link-missing}';
                }
            },
            $body
        );

        // 2. Parse Markdown to HTML
        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($body)->getContent();

        // 3. Post-process: add wiki-link classes to the links we marked
        $html = preg_replace(
            '/<a href="([^"]*)">\{\.wiki-link\}/',
            '<a href="$1" class="wiki-link">',
            $html
        );
        $html = str_replace('{.wiki-link}', '', $html);
        $html = str_replace('{.wiki-link .wiki-link-missing}', '', $html);

        // Add wiki-link class to our special links
        $html = preg_replace_callback(
            '/<a href="(\/topics\/[^"]*)">(.*?)<\/a>/',
            function ($matches) {
                $url = $matches[1];
                $text = $matches[2];
                $class = 'wiki-link';
                if (str_contains($url, 'create')) {
                    $class .= ' wiki-link-missing';
                }
                return '<a href="' . $url . '" class="' . $class . '">' . $text . '</a>';
            },
            $html
        );

        // 4. Wrap code blocks with copy button header
        $html = preg_replace_callback(
            '/<pre><code class="language-(\w+)">(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $lang = $matches[1];
                $code = $matches[2];
                return '<div class="code-block-wrapper">'
                    . '<div class="code-block-header">'
                    . '<span class="code-lang">' . $lang . '</span>'
                    . '<button class="copy-btn" onclick="copyCode(this)" title="نسخ">'
                    . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>'
                    . ' نسخ</button>'
                    . '</div>'
                    . '<pre><code class="language-' . $lang . '">' . $code . '</code></pre>'
                    . '</div>';
            },
            $html
        );

        // Also wrap plain code blocks (no language specified)
        $html = preg_replace_callback(
            '/<pre><code>(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $code = $matches[1];
                return '<div class="code-block-wrapper">'
                    . '<div class="code-block-header">'
                    . '<span class="code-lang">code</span>'
                    . '<button class="copy-btn" onclick="copyCode(this)" title="نسخ">'
                    . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>'
                    . ' نسخ</button>'
                    . '</div>'
                    . '<pre><code>' . $code . '</code></pre>'
                    . '</div>';
            },
            $html
        );

        return $html;
    }

    /**
     * Get topics that link to this topic (backlinks).
     */
    public function getBacklinksAttribute()
    {
        $pattern = '[[' . $this->title . ']]';
        return static::where('body', 'LIKE', '%' . $pattern . '%')
            ->where('id', '!=', $this->id)
            ->get();
    }

    // ─── Scopes ───

    public function scopeSearch($query, string $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%")
            ->orWhere('body', 'LIKE', "%{$term}%");
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNeedsReview($query, int $days = 7)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_reviewed_at')
              ->orWhere('last_reviewed_at', '<', now()->subDays($days));
        });
    }

    // ─── Methods ───

    /**
     * Save a version snapshot before updating.
     */
    public function saveVersion(?string $changeNote = null): void
    {
        $versionNumber = $this->versions()->count() + 1;

        TopicVersion::create([
            'topic_id' => $this->id,
            'title' => $this->getOriginal('title') ?: $this->title,
            'body' => $this->getOriginal('body') ?: $this->body,
            'version_number' => $versionNumber,
            'change_note' => $changeNote,
            'created_at' => now(),
        ]);
    }

    /**
     * Mark topic as reviewed.
     */
    public function markReviewed(): void
    {
        $this->update([
            'last_reviewed_at' => now(),
            'view_count' => $this->view_count + 1,
        ]);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
