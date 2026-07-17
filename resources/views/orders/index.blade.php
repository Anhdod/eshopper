@extends('layouts.main')

@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-12 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="align-middle">{{ $order->order_number }}</td>
                            <td class="align-middle">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="align-middle">{{ $order->items_count }}</td>
                            <td class="align-middle">${{ number_format($order->total, 2) }}</td>
                            <td class="align-middle">{{ ucfirst($order->payment_status ?? 'unpaid') }}</td>
                            <td class="align-middle">{{ ucfirst($order->status) }}</td>
                            <td class="align-middle">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4">You have no orders.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
