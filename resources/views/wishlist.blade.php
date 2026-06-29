@extends('layouts.main')

@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-12 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Action</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @if($item->product)
                            <tr>
                                <td class="align-middle">
                                    <img src="{{ asset('img/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px;">
                                    {{ $item->product->name }}
                                </td>
                                <td class="align-middle">${{ number_format($item->product->price, 2) }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('shopdetail', $item->product->id) }}" class="btn btn-sm btn-primary">View Detail</a>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('wishlist.toggle', $item->product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="4" class="py-4">Wishlist is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
