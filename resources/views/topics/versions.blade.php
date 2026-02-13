@extends('layouts.app')
@section('title', 'إصدارات: ' . $topic->title)
@section('content')

<div class="page-header">
    <div>
        <h2>📜 تاريخ الإصدارات: {{ $topic->title }}</h2>
        <div class="subtitle">{{ $versions->count() }} إصدار محفوظ</div>
    </div>
    <a href="{{ route('topics.show', $topic) }}" class="btn btn-secondary">→ رجوع للموضوع</a>
</div>

@if($versions->count())
    <ul class="version-list">
        @foreach($versions as $version)
            <li class="version-item">
                <div class="version-info">
                    <span class="version-badge">v{{ $version->version_number }}</span>
                    <div>
                        <div class="version-note">{{ $version->change_note ?? 'بدون ملاحظة' }}</div>
                        <div class="version-date">{{ $version->created_at->diffForHumans() }} · العنوان: {{ $version->title }}</div>
                    </div>
                </div>
                <div class="btn-group">
                    <form action="{{ route('topics.restoreVersion', [$topic, $version]) }}" method="POST" onsubmit="return confirm('هل تريد استعادة هذه النسخة؟')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">♻️ استعادة</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="empty-state">
        <div class="empty-icon">📜</div>
        <h3>لا يوجد إصدارات</h3>
        <p>سيتم حفظ الإصدارات تلقائياً عند تعديل الموضوع</p>
    </div>
@endif

@endsection
