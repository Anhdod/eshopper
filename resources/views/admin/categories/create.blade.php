@extends('admin.layout')
@section('title', 'Them danh muc')

@section('content')
<h3>Them danh muc</h3>
<form action="{{ route('admin.categories.store') }}" method="POST" class="col-md-6 px-0">
    @csrf
    <div class="mb-3">
        <label class="form-label">Ten danh muc</label>
        <input name="name" value="{{ old('name') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Danh muc cha</label>
        <select name="parent_id" class="form-select">
            <option value="">Khong co</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Luu</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lai</a>
</form>
@endsection
