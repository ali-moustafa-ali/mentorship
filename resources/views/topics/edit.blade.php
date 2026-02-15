@extends('layouts.app')
@section('title', 'تعديل: ' . $topic->title)
@section('content')

@php
    $domainSlug = request('domain', session('current_domain', $topic->domain?->slug ?? 'flutter'));
@endphp

<div class="page-header">
    <div>
        <h2>✏️ تعديل: {{ $topic->title }}</h2>
        <div class="subtitle">تعديل محتوى الموضوع</div>
    </div>
    <a href="{{ route('topics.show', [$topic, 'domain' => $domainSlug]) }}" class="btn btn-secondary">→ رجوع للموضوع</a>
</div>

<form action="{{ route('topics.update', [$topic, 'domain' => $domainSlug]) }}" method="POST">
    @csrf @method('PUT')

    <div class="form-group">
        <label for="title">عنوان الموضوع</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $topic->title) }}" required>
        @error('title')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="category">التصنيف</label>
        <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $topic->category) }}" list="category-list">
        <datalist id="category-list">
            @foreach($categories as $cat)
                <option value="{{ $cat }}">
            @endforeach
        </datalist>
        <div class="form-hint">اقتراحات من تصنيفات الدومين الحالي.</div>
    </div>

    <div class="form-group">
        <label for="tags">التاقات (مفصولة بفاصلة)</label>
        <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags', $topicTags) }}">
    </div>

    <div class="form-group">
        <label for="change_note">ملاحظة التعديل (اختياري)</label>
        <input type="text" name="change_note" id="change_note" class="form-control" placeholder="ماذا عدلت؟ مثال: إضافة أمثلة جديدة">
    </div>

    <div class="form-group">
        <label>المحتوى</label>
        <textarea name="body" id="body-editor" style="display:none;">{{ old('body', $topic->body) }}</textarea>

        <div class="block-editor-container">
            <div class="block-editor-pane">
                <div class="block-editor" id="block-editor"></div>
            </div>
            <div class="block-editor-pane">
                <div class="preview-pane">
                    <div class="preview-header">👁️ معاينة فورية</div>
                    <div class="topic-body" id="preview-content"></div>
                </div>
            </div>
        </div>

        <div class="form-hint">
            💡 كل بلوك قابل للسحب والترتيب. يدعم <strong>Markdown</strong>: <code>## عنوان</code> · <code>**bold**</code> · <code>- قائمة</code> · <code>```dart code```</code> · <code>[[اسم الموضوع]]</code>
        </div>
        @error('body')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">💾 حفظ التعديلات</button>
</form>

<script>
    window.defaultCodeLanguage = "{{ $defaultLanguage ?? 'plaintext' }}";
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('body-editor');
    const editor = document.getElementById('block-editor');
    const preview = document.getElementById('preview-content');
    initLivePreview(textarea, preview);
    initBlockEditor(textarea, editor, preview);
    initAutocomplete(textarea);
});
</script>

@endsection
