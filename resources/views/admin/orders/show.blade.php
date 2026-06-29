@extends('admin.layout')
@section('title', 'Chi tiet don hang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Don hang {{ $order->order_number }}</h3>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lai</a>
</div>

<div class="row">
    <div class="col-md-7">
        <table class="table table-bordered">
            <tr><th>Khach hang</th><td>{{ $order->first_name }} {{ $order->last_name }}</td></tr>
            <tr><th>Email</th><td>{{ $order->email }}</td></tr>
            <tr><th>Dien thoai</th><td>{{ $order->phone }}</td></tr>
            <tr><th>Dia chi</th><td>{{ $order->address1 }} {{ $order->address2 }} - {{ $order->city }}, {{ $order->state }}, {{ $order->country }} {{ $order->zip }}</td></tr>
            <tr><th>Payment</th><td>{{ $order->payment_method }}</td></tr>
            <tr><th>Subtotal</th><td>${{ number_format($order->subtotal, 2) }}</td></tr>
            <tr><th>Shipping</th><td>${{ number_format($order->shipping, 2) }}</td></tr>
            <tr><th>Total</th><td><strong>${{ number_format($order->total, 2) }}</strong></td></tr>
        </table>
    </div>
    <div class="col-md-5">
        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="card card-body">
            @csrf
            @method('PATCH')
            <label class="form-label">Trang thai don hang</label>
            <select name="status" class="form-select mb-3">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary">Cap nhat trang thai</button>
        </form>
    </div>
</div>

<h5 class="mt-4">San pham</h5>
<table class="table table-bordered">
    <thead><tr><th>Ten</th><th>Gia</th><th>SL</th><th>Mau</th><th>Size</th><th>Tong</th></tr></thead>
    <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->color }}</td>
                <td>{{ $item->size }}</td>
                <td>${{ number_format($item->total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
