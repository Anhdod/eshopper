@extends('admin.layout')
@section('title', 'Quan ly menus')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Menus</h3>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Them menu</a>
</div>

<table class="table table-bordered table-hover">
    <thead><tr><th>ID</th><th>Ten</th><th>Route</th><th>Type</th><th>Menu cha</th><th>Thu tu</th><th></th></tr></thead>
    <tbody>
        @forelse($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>
                <td>{{ $menu->name }}</td>
                <td>{{ $menu->route ?: '-' }}</td>
                <td>{{ $menu->type }}</td>
                <td>{{ $menu->parent->name ?? '-' }}</td>
                <td>{{ $menu->order }}</td>
                <td>
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-warning">Sua</a>
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xoa menu nay?')">Xoa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Chua co menu.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $menus->links() }}
@endsection
