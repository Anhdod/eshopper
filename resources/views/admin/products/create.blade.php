@extends('admin.layout')
@section('title', 'Them san pham')

@section('content')
<h3>Them san pham moi</h3>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label>Ten san pham</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Danh muc</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chon danh muc --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>-- {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Gia ban</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gia goc</label>
                    <input type="number" name="original_price" value="{{ old('original_price') }}" step="0.01" class="form-control">
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
                <label>Hinh dai dien</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label>Gallery images</label>
                <input type="file" name="gallery_images[]" class="form-control" multiple>
                <small class="text-muted">Upload multiple extra product images.</small>
            </div>

            <button type="submit" class="btn btn-success">Them san pham</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lai</a>
        </div>
    </div>
</form>
@endsection
