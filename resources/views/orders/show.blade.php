@extends('layouts.main')

@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="align-middle">{{ $item->product_name }}</td>
                            <td class="align-middle">${{ number_format($item->price, 2) }}</td>
                            <td class="align-middle">{{ $item->quantity }}</td>
                            <td class="align-middle">{{ $item->size ?? '-' }}</td>
                            <td class="align-middle">{{ $item->color ?? '-' }}</td>
                            <td class="align-middle">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong>${{ number_format($order->total, 2) }}</strong>
                    </div>
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p class="mb-0"><strong>Address:</strong> {{ $order->address1 }}, {{ $order->city }}, {{ $order->country }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
