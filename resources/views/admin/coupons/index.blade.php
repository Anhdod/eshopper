@extends('admin.layout')
@section('title', 'Coupons')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Coupons</h3>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create coupon</a>
</div>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Value</th>
            <th>Minimum</th>
            <th>Used</th>
            <th>Status</th>
            <th>Expires</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td><strong>{{ $coupon->code }}</strong></td>
                <td>{{ ucfirst($coupon->type) }}</td>
                <td>{{ $coupon->type === 'percent' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}</td>
                <td>${{ number_format($coupon->minimum_order, 2) }}</td>
                <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}</td>
                <td>
                    <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ optional($coupon->expires_at)->format('d/m/Y H:i') ?? 'No limit' }}</td>
                <td>
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this coupon?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No coupons yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $coupons->links() }}
@endsection
