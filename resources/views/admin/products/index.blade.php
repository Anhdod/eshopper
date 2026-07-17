@extends('admin.layout')
@section('title', 'Quan ly san pham')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Danh sach san pham</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Them san pham</a>
</div>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hinh</th>
            <th>Ten</th>
            <th>Danh muc</th>
            <th>Gia</th>
            <th>Ton kho</th>
            <th>Hanh dong</th>
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
            <td>${{ number_format($product->price, 2) }}</td>
            <td>
                @if($product->stock === null)
                    <span class="text-muted">Unlimited</span>
                @elseif($product->stock <= 5)
                    <span class="badge bg-danger">{{ $product->stock }}</span>
                @else
                    {{ $product->stock }}
                @endif
            </td>
            <td>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Sua</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xoa san pham nay?')">Xoa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $products->links() }}
@endsection
