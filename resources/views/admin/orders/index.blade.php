@extends('admin.layout')
@section('title', 'Quan ly don hang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Don hang</h3>
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select">
            <option value="">Tat ca trang thai</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary">Loc</button>
    </form>
</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Ma don</th>
            <th>Khach hang</th>
            <th>So SP</th>
            <th>Tong tien</th>
            <th>Thanh toan</th>
            <th>Trang thai</th>
            <th>Ngay dat</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->first_name }} {{ $order->last_name }}<br><small>{{ $order->email }}</small></td>
                <td>{{ $order->items_count }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td>{{ $order->payment_method }}</td>
                <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">Chi tiet</a></td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center">Chua co don hang.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $orders->links() }}
@endsection
