@extends('layouts.admin')

@section('title', 'تعديل دومين')

@section('content')
<div class="page-header">
    <h2>تعديل دومين: {{ $domain->name }}</h2>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.domains.update', $domain->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="margin-bottom: 15px;">
            <label>اسم الدومين</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $domain->name) }}" placeholder="مثال: Flutter">
            @error('name') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>Slug (عنوان URL)</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug', $domain->slug) }}" placeholder="مثال: flutter">
            <small style="color: var(--text-muted);">للاستخدام في الرابط. تغيير هذا قد يؤدي لتغيير روابط المواضيع!</small>
            @error('slug') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>اللغة الافتراضية للكود</label>
            <select name="default_language" class="form-control">
                @foreach(['plaintext', 'dart', 'php', 'javascript', 'python', 'cpp', 'html', 'css', 'swift', 'java'] as $lang)
                    <option value="{{ $lang }}" {{ old('default_language', $domain->default_language) == $lang ? 'selected' : '' }}>
                        {{ ucfirst($lang) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>الأيقونة (Emoji أو نص)</label>
            <input type="text" name="icon" class="form-control" required value="{{ old('icon', $domain->icon) }}" placeholder="مثال: 💙">
            @error('icon') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label>اللون (Hex)</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color" class="form-control" style="width: 60px; height: 40px; padding: 2px;" required value="{{ old('color', $domain->color) }}">
                <span style="color: var(--text-muted);">اختر لوناً مميزاً للثيم.</span>
            </div>
            @error('color') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
