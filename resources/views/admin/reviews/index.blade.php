@extends('admin.layout')
@section('title', 'Quan ly reviews')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Reviews</h3>
    <form method="GET" class="d-flex gap-2">
        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Tim ten, email, noi dung">
        <select name="rating" class="form-select">
            <option value="">Tat ca sao</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ (string) request('rating') === (string) $i ? 'selected' : '' }}>{{ $i }} sao</option>
            @endfor
        </select>
        <select name="status" class="form-select">
            <option value="">Tat ca trang thai</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Cho duyet</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Da duyet</option>
        </select>
        <button class="btn btn-primary">Loc</button>
    </form>
</div>

<table class="table table-bordered table-hover">
    <thead><tr><th>San pham</th><th>Nguoi gui</th><th>Rating</th><th>Trang thai</th><th>Noi dung</th><th>Ngay</th><th></th></tr></thead>
    <tbody>
        @forelse($reviews as $review)
            <tr>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
                <td>{{ $review->name }}<br><small>{{ $review->email }}</small></td>
                <td>{{ $review->rating }}/5</td>
                <td>
                    <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $review->is_approved ? 'Da duyet' : 'Cho duyet' }}
                    </span>
                </td>
                <td>{{ Str::limit($review->message, 120) }}</td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.reviews.approval', $review) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                        <button class="btn btn-sm {{ $review->is_approved ? 'btn-secondary' : 'btn-success' }}">
                            {{ $review->is_approved ? 'An' : 'Duyet' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xoa review nay?')">Xoa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Chua co review.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $reviews->links() }}
@endsection
