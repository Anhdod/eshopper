@extends('admin.layout')
@section('title', 'Quan ly lien he')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Lien he</h3>
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select">
            <option value="">Tat ca</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Chua doc</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Da doc</option>
        </select>
        <button class="btn btn-primary">Loc</button>
    </form>
</div>

<table class="table table-bordered table-hover">
    <thead><tr><th>Ten</th><th>Email</th><th>Chu de</th><th>Trang thai</th><th>Ngay gui</th><th></th></tr></thead>
    <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->subject }}</td>
                <td><span class="badge {{ $contact->is_read ? 'bg-success' : 'bg-warning text-dark' }}">{{ $contact->is_read ? 'Da doc' : 'Chua doc' }}</span></td>
                <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">Doc</a>
                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xoa lien he nay?')">Xoa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Chua co lien he.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $contacts->links() }}
@endsection
