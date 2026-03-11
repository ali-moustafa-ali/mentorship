@extends('layouts.admin')

@section('title', 'تعديل تاق')

@section('content')
<div class="page-header">
    <h2>تعديل تاق: {{ $tag->name }}</h2>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div style="margin-bottom: 15px;">
            <label>اسم التاق</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $tag->name) }}">
            @error('name') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>Slug (URL)</label>
            <input type="text" name="slug" class="form-control" required value="{{ old('slug', $tag->slug) }}">
            @error('slug') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label>اللون (Hex)</label>
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="color" name="color" class="form-control" style="width: 60px; height: 40px; padding: 2px;" required value="{{ old('color', $tag->color) }}">
                <span class="tag-pill" style="background: {{ old('color', $tag->color) }}; color: #fff; padding: 4px 8px; border-radius: 12px; font-size: 0.9rem;">معاينة</span>
            </div>
            @error('color') <small style="color: var(--error);">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
