@extends('admin.layout')
@section('title', 'Sua san pham')

@section('content')
<h3>Sua san pham</h3>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label>Ten san pham</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Danh muc</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chon danh muc --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @foreach($cat->children as $child)
                            <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                -- {{ $child->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Gia ban</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gia goc</label>
                    <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" step="0.01" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Mo ta</label>
                <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Mau sac</label>
                    <input type="text" name="color" value="{{ old('color', implode(', ', $product->color ?? [])) }}" class="form-control" placeholder="red, blue, black">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Size</label>
                    <input type="text" name="sizes" value="{{ old('sizes', implode(', ', $product->sizes ?? [])) }}" class="form-control" placeholder="S, M, L">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Ton kho</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Hinh anh hien tai</label><br>
                @if($product->image)
                    <img src="{{ asset('img/' . $product->image) }}" alt="{{ $product->name }}" width="100" class="mb-2">
                @else
                    <p class="text-muted">Khong co hinh</p>
                @endif
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Cap nhat</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lai</a>
        </div>
    </div>
</form>
@endsection
