@extends('admin.layout')
@section('title', 'Them menu')

@section('content')
<h3>Them menu</h3>
<form action="{{ route('admin.menus.store') }}" method="POST" class="col-md-7 px-0">
    @csrf
    @include('admin.menus.form', ['menu' => null])
    <button class="btn btn-success">Luu</button>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Quay lai</a>
</form>
@endsection
