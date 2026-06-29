@extends('admin.layout')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Danh sách sản phẩm</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hình</th>
            <th>Tên</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>
                @if($product->image)
                    <img src="{{ asset('img/' . $product->image) }}" alt="{{ $product->name }}" width="50">
                @else
                    <span class="text-muted">No image</span>
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? 'N/A' }}</td>
            <td>${{ number_format($product->price,2) }}</td>
            <td>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection