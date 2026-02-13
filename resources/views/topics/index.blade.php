@extends('layouts.app')
@section('title', 'كل المواضيع')
@section('content')

<div class="page-header">
    <div>
        <h2>📚 كل المواضيع</h2>
        @if(request('category'))
            <div class="subtitle">تصفية: {{ request('category') }}</div>
        @elseif(request('tag'))
            <div class="subtitle">تاق: {{ request('tag') }}</div>
        @endif
    </div>
    <a href="{{ route('topics.create', ['domain' => $currentDomain->slug]) }}" class="btn btn-primary">✏️ موضوع جديد</a>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-icon" style="background: var(--accent-glow);">📝</div>
        <div>
            <div class="stat-number">{{ $topics->count() }}</div>
            <div class="stat-label">موضوع</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-icon" style="background: rgba(74,222,128,0.15);">📂</div>
        <div>
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">تصنيف</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-icon" style="background: rgba(251,191,36,0.15);">🏷️</div>
        <div>
            <div class="stat-number">{{ $tags->count() }}</div>
            <div class="stat-label">تاق</div>
        </div>
    </div>
    @if($reviewCount > 0)
    <div class="stat-item">
        <div class="stat-icon" style="background: rgba(248,113,113,0.15);">🔄</div>
        <div>
            <div class="stat-number">{{ $reviewCount }}</div>
            <div class="stat-label">بحاجة لمراجعة</div>
        </div>
    </div>
    @endif
</div>

@if($topics->count())
    <div class="topics-grid">
        @foreach($topics as $topic)
            <a href="{{ route('topics.show', [$topic, 'domain' => $currentDomain->slug]) }}" class="topic-card">
                <div class="card-top">
                    <h3>{{ $topic->title }}</h3>
                    @if($topic->is_pinned)
                        <span class="pin-star">⭐</span>
                    @endif
                </div>
                <div class="card-body-preview">{{ Str::limit(strip_tags($topic->body), 120) }}</div>
                <div class="card-footer">
                    <div class="card-tags">
                        @if($topic->category)
                            <span class="card-tag" style="background: var(--accent);">{{ $topic->category }}</span>
                        @endif
                        @foreach($topic->tags->take(3) as $tag)
                            <span class="card-tag" style="background: {{ $tag->color }};">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <span class="card-date">{{ $topic->updated_at->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">📖</div>
        <h3>لا يوجد مواضيع بعد</h3>
        <p>ابدأ بإنشاء أول موضوع لتبني قاعدة معرفتك</p>
        <a href="{{ route('topics.create', ['domain' => $currentDomain->slug]) }}" class="btn btn-primary">✏️ أنشئ موضوع</a>
    </div>
@endif

@endsection
