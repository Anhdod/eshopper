@extends('admin.layout')
@section('title', 'Revenue Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Revenue report</h3>
    <form method="GET" class="d-flex gap-2">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        <button class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success"><div class="card-body"><h6>Revenue</h6><h3>${{ number_format($summary['revenue'], 2) }}</h3></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary"><div class="card-body"><h6>Completed orders</h6><h3>{{ $summary['completed_orders'] }}</h3></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning"><div class="card-body"><h6>Discounts</h6><h3>${{ number_format($summary['discounts'], 2) }}</h3></div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger"><div class="card-body"><h6>Low stock</h6><h3>{{ $summary['low_stock'] }}</h3></div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <h5>Top products</h5>
        <table class="table table-bordered">
            <thead><tr><th>Product</th><th>Sold</th><th>Revenue</th></tr></thead>
            <tbody>
                @forelse($topProducts as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->sold_qty }}</td>
                        <td>${{ number_format($item->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">No completed sales.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-lg-6">
        <h5>Recent orders</h5>
        <table class="table table-bordered">
            <thead><tr><th>Order</th><th>Status</th><th>Total</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No orders.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
