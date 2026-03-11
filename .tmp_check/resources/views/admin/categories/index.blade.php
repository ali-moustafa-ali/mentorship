@extends('layouts.admin')

@section('title', 'إدارة التصنيفات')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>التصنيفات</h2>
        
        <form action="{{ route('admin.categories.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <label style="margin:0; font-size: 0.9rem; color: var(--text-muted);">تصفية حسب:</label>
            <select name="domain" class="form-control" onchange="this.form.submit()" style="width: auto; margin:0; padding: 6px 12px; height: auto;">
                <option value="">📂 كل الدومينات</option>
                @foreach($domains as $domain)
                    <option value="{{ $domain->id }}" {{ request('domain') == $domain->id ? 'selected' : '' }}>
                        {{ $domain->icon }} {{ $domain->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>التصنيف</th>
                <th>عدد المواضيع</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->category }}</td>
                <td>{{ $category->count }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <button onclick="renameCategory('{{ $category->category }}')" class="btn btn-sm btn-secondary" title="إعادة تسمية">✏️</button>
                        
                        <form action="{{ route('admin.categories.destroy') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('⚠️ تحذير: سيقوم هذا بإلغاء تصنيف جميع المواضيع المرتبطة بهذا التصنيف. هل أنت متأكد؟');">
                            @csrf
                            <input type="hidden" name="name" value="{{ $category->category }}">
                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<form id="rename-form" action="{{ route('admin.categories.rename') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="old_name" id="rename-old">
    <input type="hidden" name="new_name" id="rename-new">
</form>

<script>
function renameCategory(oldName) {
    const newName = prompt('الاسم الجديد للتصنيف:', oldName);
    if (newName && newName !== oldName) {
        document.getElementById('rename-old').value = oldName;
        document.getElementById('rename-new').value = newName;
        document.getElementById('rename-form').submit();
    }
}
</script>
@endsection
