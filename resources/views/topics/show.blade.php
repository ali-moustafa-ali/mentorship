@extends('layouts.app')
@section('title', $topic->title)
@section('content')

@php
    $domainSlug = request('domain', session('current_domain', $topic->domain?->slug ?? 'flutter'));
    $indexBaseUrl = rtrim(config('app.url'), '/') . '/';
@endphp

<div class="page-header">
    <div>
        <h2>{{ $topic->title }}</h2>
        <div class="subtitle">
            @if($topic->category)
                <span style="color: var(--accent-light);">{{ $topic->category }}</span> ·
            @endif
            آخر تحديث: {{ $topic->updated_at->diffForHumans() }}
            · {{ $topic->view_count }} مشاهدة
        </div>
    </div>
    <div class="btn-group">
        <form action="{{ route('topics.togglePin', [$topic, 'domain' => $domainSlug]) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-sm {{ $topic->is_pinned ? 'btn-warning' : 'btn-secondary' }}" title="{{ $topic->is_pinned ? 'إلغاء التثبيت' : 'تثبيت' }}">
                {{ $topic->is_pinned ? '⭐ مثبت' : '☆ تثبيت' }}
            </button>
        </form>
        <a href="{{ route('topics.versions', [$topic, 'domain' => $domainSlug]) }}" class="btn btn-sm btn-secondary">📜 الإصدارات</a>
        <a href="{{ route('topics.export', ['topic' => $topic, 'format' => 'markdown', 'domain' => $domainSlug]) }}" class="btn btn-sm btn-secondary">📥 تصدير</a>
        <a href="{{ route('topics.edit', [$topic, 'domain' => $domainSlug]) }}" class="btn btn-sm btn-secondary">✏️ تعديل</a>
        <form action="{{ route('topics.destroy', [$topic, 'domain' => $domainSlug]) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">🗑 حذف</button>
        </form>
    </div>
</div>

@if($topic->tags->count())
    <div style="margin-bottom: 16px;">
        @foreach($topic->tags as $tag)
            <a href="{{ $indexBaseUrl . '?' . http_build_query(['tag' => $tag->slug, 'domain' => $domainSlug]) }}" class="tag-pill" style="--tag-color: {{ $tag->color }};">{{ $tag->name }}</a>
        @endforeach
    </div>
@endif

<div class="topic-content">
    <div class="topic-body">
        {!! $topic->rendered_body !!}
    </div>
</div>

@if($backlinks->count())
    <div class="backlinks-section">
        <h3>🔗 مواضيع مرتبطة (بتشير لهنا)</h3>
        <div class="backlinks-list">
            @foreach($backlinks as $backlink)
                <a href="{{ route('topics.show', [$backlink, 'domain' => $domainSlug]) }}" class="backlink-item">{{ $backlink->title }}</a>
            @endforeach
        </div>
    </div>
@endif

@endsection
