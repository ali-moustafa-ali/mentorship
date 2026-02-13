@extends('layouts.app')
@section('title', 'مراجعة المواضيع')
@section('content')

@php
    $domainSlug = request('domain', session('current_domain', $currentDomain->slug ?? 'flutter'));
@endphp

<div class="page-header">
    <div>
        <h2>🔄 مواضيع تحتاج مراجعة</h2>
        <div class="subtitle">مواضيع لم تراجعها منذ أكثر من 7 أيام</div>
    </div>
</div>

@if($topics->count())
    <div class="topics-grid">
        @foreach($topics as $topic)
            <a href="{{ route('topics.show', [$topic, 'domain' => $domainSlug]) }}" class="topic-card">
                <div class="card-top">
                    <h3>{{ $topic->title }}</h3>
                    @if($topic->is_pinned)
                        <span class="pin-star">⭐</span>
                    @endif
                </div>
                <div class="card-body-preview">{{ Str::limit(strip_tags($topic->body), 100) }}</div>
                <div class="card-footer">
                    <div class="card-tags">
                        @if($topic->category)
                            <span class="card-tag" style="background: var(--accent);">{{ $topic->category }}</span>
                        @endif
                    </div>
                    <span class="card-date">
                        @if($topic->last_reviewed_at)
                            آخر مراجعة: {{ $topic->last_reviewed_at->diffForHumans() }}
                        @else
                            لم يتم مراجعته بعد
                        @endif
                    </span>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon">✅</div>
        <h3>كل المواضيع مراجعة!</h3>
        <p>لا يوجد مواضيع تحتاج مراجعة حالياً. ارجع بعد أسبوع.</p>
    </div>
@endif

@endsection
