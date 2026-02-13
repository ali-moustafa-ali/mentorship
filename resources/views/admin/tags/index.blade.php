@extends('layouts.admin')

@section('title', 'إدارة التاقات')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>التاقات</h2>
        
        <form action="{{ route('admin.tags.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <label style="margin:0; font-size: 0.9rem; color: var(--text-muted);">تصفية حسب:</label>
            <select name="domain" class="form-control" onchange="this.form.submit()" style="width: auto; margin:0; padding: 6px 12px; height: auto;">
                <option value="">🌐 كل الدومينات</option>
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
                <th>الاسم</th>
                <th>Slug</th>
                <th>اللون</th>
                <th>عدد المواضيع</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td>
                    <span style="display:inline-block; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; background:{{ $tag->color }}; color:#fff;">
                        {{ $tag->name }}
                    </span>
                </td>
                <td>{{ $tag->topics_count }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-sm btn-secondary" title="تعديل">✏️</a>
                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف التاق؟ سيتم إزالته من جميع المواضيع.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
