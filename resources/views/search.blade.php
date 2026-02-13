@extends('layouts.app')
@section('title', 'بحث')
@section('content')

@php
    $domainSlug = request('domain', session('current_domain'));
@endphp

<div class="page-header">
    <div>
        <h2>🔍 بحث في المواضيع</h2>
        <div class="subtitle">ابحث بالعنوان أو المحتوى</div>
    </div>
</div>

<form action="{{ route('search') }}" method="GET" class="search-page-input">
    @if($domainSlug)
        <input type="hidden" name="domain" value="{{ $domainSlug }}">
    @endif
    <input type="text" name="q" class="form-control" value="{{ $query }}" placeholder="اكتب كلمة البحث..." autofocus>
    <button type="submit" class="btn btn-primary">بحث</button>
</form>

@if($query)
    <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 0.85rem;">
        تم إيجاد <strong style="color: var(--accent-light);">{{ $results->count() }}</strong> نتيجة للبحث عن "<strong style="color:var(--accent-light);">{{ $query }}</strong>"
    </p>

    @forelse($results as $topic)
        <div class="search-result-item">
            <h3>
                <a href="{{ route('topics.show', [$topic, 'domain' => $domainSlug]) }}">{{ $topic->title }}</a>
                @if($topic->category)
                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-right: 8px;">{{ $topic->category }}</span>
                @endif
            </h3>
            <div class="preview">{{ Str::limit(strip_tags($topic->body), 200) }}</div>
            @if($topic->tags->count())
                <div style="margin-top: 6px;">
                    @foreach($topic->tags as $tag)
                        <span class="card-tag" style="background: {{ $tag->color }}; font-size: 0.68rem;">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
            <h3>لا توجد نتائج</h3>
            <p>لم يتم العثور على مواضيع تطابق "{{ $query }}"</p>
            <a href="{{ route('topics.create', ['title' => $query, 'domain' => ($domainSlug ?? session('current_domain') ?? 'flutter')]) }}" class="btn btn-primary">✏️ أنشئ موضوع "{{ $query }}"</a>
        </div>
    @endforelse
@endif

@endsection
