@extends('admin.layout')
@section('title', 'Sua danh muc')

@section('content')
<h3>Sua danh muc</h3>
<form action="{{ route('admin.categories.update', $category) }}" method="POST" class="col-md-6 px-0">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Ten danh muc</label>
        <input name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Danh muc cha</label>
        <select name="parent_id" class="form-select">
            <option value="">Khong co</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-success">Cap nhat</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lai</a>
</form>
@endsection
