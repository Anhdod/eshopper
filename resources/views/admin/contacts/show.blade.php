@extends('admin.layout')
@section('title', 'Chi tiet lien he')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>{{ $contact->subject }}</h3>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lai</a>
</div>

<table class="table table-bordered">
    <tr><th>Ten</th><td>{{ $contact->name }}</td></tr>
    <tr><th>Email</th><td>{{ $contact->email }}</td></tr>
    <tr><th>Trang thai</th><td>{{ $contact->is_read ? 'Da doc' : 'Chua doc' }}</td></tr>
    <tr><th>Noi dung</th><td>{!! nl2br(e($contact->message)) !!}</td></tr>
</table>

<a href="mailto:{{ $contact->email }}?subject=Re:%20{{ rawurlencode($contact->subject) }}" class="btn btn-primary">Phan hoi qua email</a>
<form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
    @csrf @method('DELETE')
    <button class="btn btn-danger" onclick="return confirm('Xoa lien he nay?')">Xoa</button>
</form>
@endsection
