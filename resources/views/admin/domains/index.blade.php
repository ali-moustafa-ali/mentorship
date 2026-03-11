@extends('layouts.admin')

@section('title', 'إدارة الدومينات')

@section('content')
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>الدومينات</h2>
        <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">➕ دومين جديد</a>
    </div>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>Slug</th>
                <th>الأيقونة</th>
                <th>اللون</th>
                <th>المواضيع</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($domains as $domain)
            <tr>
                <td>{{ $domain->name }}</td>
                <td>{{ $domain->slug }}</td>
                <td><span style="font-size: 1.5rem;">{{ $domain->icon }}</span></td>
                <td><span style="display:inline-block; width:20px; height:20px; background:{{ $domain->color }}; border-radius:50%; border: 1px solid var(--border);"></span> {{ $domain->color }}</td>
                <td>{{ $domain->topics_count }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <a href="{{ route('admin.domains.edit', $domain->id) }}" class="btn btn-sm btn-secondary">✏️ تعديل</a>
                        <form action="{{ route('admin.domains.destroy', $domain->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('⚠️ تحذير: حذف الدومين قد يؤدي لحذف المواضيع المرتبطة أو جعلها orphaned. هل أنت متأكد؟');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️ حذف</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
