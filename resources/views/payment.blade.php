@extends('layouts.main')
@section('content')
<div class="container-fluid py-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6">
            <div class="card border-secondary">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Online Payment</h4>
                </div>
                <div class="card-body">
                    <p class="mb-2">Order: <strong>{{ $order->order_number }}</strong></p>
                    <p class="mb-2">Payment method: <strong>{{ ucfirst($order->payment_method) }}</strong></p>
                    <p class="mb-2">Payment status: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <h5>${{ number_format($order->total, 2) }}</h5>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    @if($order->payment_status === 'paid')
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-block">View Order</a>
                    @else
                        <form action="{{ route('payment.complete', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-block">Confirm Payment</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
