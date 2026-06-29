@extends('admin.layout')
@section('title', 'Thêm sản phẩm')

@section('content')
<h3>Thêm sản phẩm mới</h3>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Danh mục</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Giá bán</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Giá gốc</label>
                    <input type="number" name="original_price" step="0.01" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Mo ta</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Mau sac</label>
                    <input type="text" name="color" value="{{ old('color') }}" class="form-control" placeholder="red, blue, black">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Size</label>
                    <input type="text" name="sizes" value="{{ old('sizes') }}" class="form-control" placeholder="S, M, L">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Ton kho</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="0" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Hình ảnh</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</form>
@endsection
