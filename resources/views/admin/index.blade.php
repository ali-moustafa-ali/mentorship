@extends('layouts.admin')

@section('title', 'الرئيسية')

@section('content')
<div class="page-header">
    <h2>لوحة التحكم</h2>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
    <div class="card" style="border-right: 4px solid var(--accent);">
        <h3 style="color: var(--text-muted); font-size: 0.9rem;">الدومينات</h3>
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $stats['domains'] }}</div>
    </div>
    
    <div class="card" style="border-right: 4px solid var(--accent);">
        <h3 style="color: var(--text-muted); font-size: 0.9rem;">المواضيع</h3>
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $stats['topics'] }}</div>
    </div>

    <div class="card" style="border-right: 4px solid var(--warning);">
        <h3 style="color: var(--text-muted); font-size: 0.9rem;">بانتظار المراجعة</h3>
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $stats['reviews_pending'] }}</div>
    </div>

    <div class="card">
        <h3 style="color: var(--text-muted); font-size: 0.9rem;">التصنيفات</h3>
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $stats['categories'] }}</div>
    </div>

    <div class="card">
        <h3 style="color: var(--text-muted); font-size: 0.9rem;">التاقات</h3>
        <div style="font-size: 2.5rem; font-weight: bold;">{{ $stats['tags'] }}</div>
    </div>
</div>

<div class="card" style="margin-top: 30px;">
    <h3>روابط سريعة</h3>
    <div style="display: flex; gap: 10px; margin-top: 15px;">
        <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">➕ إضافة دومين جديد</a>
        <a href="{{ route('topics.create', ['domain' => session('current_domain', 'flutter')]) }}" class="btn btn-secondary">✏️ كتابة موضوع جديد</a>
    </div>
</div>
@endsection
