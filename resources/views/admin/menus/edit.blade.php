@extends('admin.layout')
@section('title', 'Sua menu')

@section('content')
<h3>Sua menu</h3>
<form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="col-md-7 px-0">
    @csrf
    @method('PUT')
    @include('admin.menus.form', ['menu' => $menu])
    <button class="btn btn-success">Cap nhat</button>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Quay lai</a>
</form>
@endsection
