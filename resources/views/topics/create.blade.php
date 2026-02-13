@extends('layouts.app')
@section('title', 'موضوع جديد')
@section('content')

@php
    $indexBaseUrl = rtrim(config('app.url'), '/') . '/';
@endphp

<div class="page-header">
    <div>
        <h2>✏️ موضوع جديد</h2>
        <div class="subtitle">أضف معرفة جديدة لقاعدة البيانات</div>
    </div>
    <a href="{{ $indexBaseUrl . '?' . http_build_query(['domain' => $domain->slug]) }}" class="btn btn-secondary">→ رجوع</a>
</div>

<form action="{{ route('topics.store', ['domain' => $domain->slug]) }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="title">عنوان الموضوع</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $title) }}" required autofocus placeholder="مثال: Widgets الأساسية">
        @error('title')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="category">التصنيف</label>
        <input type="text" name="category" id="category" class="form-control" value="{{ old('category') }}" placeholder="مثال: أساسيات، متقدم" list="category-list" autocomplete="off">
        <datalist id="category-list">
            @foreach($categories as $cat)
                <option value="{{ $cat }}">
            @endforeach
        </datalist>
    </div>

    <div class="form-group">
        <label for="tags">التاقات (مفصولة بفاصلة)</label>
        <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags') }}" placeholder="مثال: widgets, ui, basics">
        
        @if(isset($tags) && $tags->count() > 0)
        <div class="popular-tags" style="margin-top: 10px;">
            <div style="font-size: 0.8rem; margin-bottom: 6px; color: var(--text-muted);">تاقات شائعة في هذا القسم:</div>
            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                @foreach($tags as $tag)
                    <span onclick="addTag('{{ $tag->name }}')" 
                          style="background: {{ $tag->color }}20; color: {{ $tag->color }}; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; cursor: pointer; border: 1px solid {{ $tag->color }}40; transition: all 0.2s;"
                          onmouseover="this.style.background='{{ $tag->color }}40'"
                          onmouseout="this.style.background='{{ $tag->color }}20'">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
        
        <script>
        function addTag(tagName) {
            const input = document.getElementById('tags');
            let current = input.value.trim();
            // Remove trailing comma if exists to avoid double comma
            if (current.endsWith(',')) current = current.slice(0, -1).trim();
            
            if (current.length > 0) {
                // Check if tag already exists
                const tagsIndex = current.split(',').map(t => t.trim());
                if (!tagsIndex.includes(tagName)) {
                    input.value = current + ', ' + tagName;
                }
            } else {
                input.value = tagName;
            }
            input.focus();
        }
        </script>
        <div class="form-hint">اكتب أو اختر التاقات.</div>
    </div>

    <div class="form-group">
        <label>المحتوى</label>
        <textarea name="body" id="body-editor" style="display:none;">{{ old('body') }}</textarea>

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
            💡 كل بلوك قابل للسحب والترتيب. يدعم <strong>Markdown</strong>: <code>## عنوان</code> · <code>**bold**</code> · <code>- قائمة</code> · <code>```dart code```</code> · <code>[[اسم الموضوع]]</code> لربط المواضيع
        </div>
        @error('body')<div class="error-msg">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">💾 حفظ الموضوع</button>
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
