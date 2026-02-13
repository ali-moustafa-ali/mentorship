@extends('layouts.admin')

@section('title', 'إضافة دومين')

@section('content')
<div class="page-header">
    <h2>إضافة دومين جديد</h2>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.domains.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label>اسم الدومين</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="مثال: Flutter">
            @error('name') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>Slug (عنوان URL)</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug') }}" placeholder="مثال: flutter">
            <small style="color: var(--text-muted);">للاستخدام في الرابط، حروف إنجليزية فقط.</small>
            @error('slug') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>اللغة الافتراضية للكود</label>
            <select name="default_language" class="form-control">
                <option value="plaintext">نص عادي (Plaintext)</option>
                <option value="dart">Dart / Flutter</option>
                <option value="php">PHP / Laravel</option>
                <option value="javascript">JavaScript</option>
                <option value="python">Python</option>
                <option value="cpp">C++</option>
                <option value="html">HTML</option>
                <option value="css">CSS</option>
                <option value="swift">Swift</option>
                <option value="java">Java</option>
            </select>
            <small style="color: var(--text-muted);">سيتم اختيار هذه اللغة تلقائياً عند إضافة "Code Block" جديد.</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label>الأيقونة (Emoji أو نص)</label>
            <input type="text" name="icon" class="form-control" required value="{{ old('icon') }}" placeholder="مثال: 💙">
            @error('icon') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label>اللون (Hex)</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color" class="form-control" style="width: 60px; height: 40px; padding: 2px;" required value="{{ old('color', '#3b82f6') }}">
                <span style="color: var(--text-muted);">اختر لوناً مميزاً للثيم.</span>
            </div>
            @error('color') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">حفظ الدومين</button>
        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
