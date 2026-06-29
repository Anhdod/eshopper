@extends('admin.layout')
@section('title', 'Quan ly danh muc')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Danh muc</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Them danh muc</a>
</div>

<table class="table table-bordered table-hover">
    <thead><tr><th>ID</th><th>Ten</th><th>Danh muc cha</th><th>So san pham</th><th></th></tr></thead>
    <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->parent->name ?? '-' }}</td>
                <td>{{ $category->products_count }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sua</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xoa danh muc nay?')">Xoa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">Chua co danh muc.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $categories->links() }}
@endsection
