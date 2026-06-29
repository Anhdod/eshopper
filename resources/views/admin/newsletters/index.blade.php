@extends('admin.layout')
@section('title', 'Quan ly newsletter')

@section('content')
<h3 class="mb-3">Newsletter subscribers</h3>
<table class="table table-bordered table-hover">
    <thead><tr><th>Ten</th><th>Email</th><th>Ngay dang ky</th><th></th></tr></thead>
    <tbody>
        @forelse($newsletters as $newsletter)
            <tr>
                <td>{{ $newsletter->name ?: '-' }}</td>
                <td>{{ $newsletter->email }}</td>
                <td>{{ $newsletter->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.newsletters.destroy', $newsletter) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xoa email nay?')">Xoa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">Chua co subscriber.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $newsletters->links() }}
@endsection
