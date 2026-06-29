@extends('layouts.main')
@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Size and color</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    @forelse($cartItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="align-middle">
                                <img src="{{ asset('img/' . $item->product->image) }}" style="width: 50px;">
                                {{ $item->product->name }}
                            </td>
                            <td class="align-middle price">${{ number_format($item->product->price, 2) }}</td>
                          <td class="align-middle">
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-sm btn-primary decrement-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                    <i class="fa fa-minus"></i>
                                </button>
                                <input type="number" class="form-control form-control-sm mx-2 text-center qty-input"
                                    value="{{ $item->quantity }}" min="1" style="width: 60px;" data-id="{{ $item->id }}">

                                <button class="btn btn-sm btn-primary increment-btn">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="align-middle">
    <!-- Sizes -->
                        @if(!empty($item->product->sizes))
                        <select class="form-control form-control-sm size-select" data-id="{{ $item->id }}">
                            @foreach($item->product->sizes as $size)
                                <option value="{{ $size }}" {{ $item->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        @endif

                        <!-- Colors -->
                        @if(!empty($item->product->color))
                        <select class="form-control form-control-sm mt-1 color-select" data-id="{{ $item->id }}">
                            @foreach($item->product->color as $color)
                                <option value="{{ $color }}" {{ $item->color == $color ? 'selected' : '' }}>{{ ucfirst($color) }}</option>
                            @endforeach
                        </select>
                        @endif
                    </td>

                            <td class="align-middle item-total">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger remove-btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-cart">
                            <td colspan="6" class="text-center py-4">Giỏ hàng trống</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 pt-1">
                        <h6>Subtotal</h6>
                        <h6 id="subtotal">${{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 2) }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Shipping</h6>
                        <h6>$10.00</h6>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-secondary">
                    <div class="d-flex justify-content-between mt-2">
                        <h5>Total</h5>
                        <h5 id="total">${{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity) + 10, 2) }}</h5>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = '{{ url('') }}';

    const updateSummary = () => {
        const subtotal = Array.from(document.querySelectorAll('.item-total'))
            .reduce((sum, el) => sum + parseFloat(el.textContent.replace('$', '')), 0);
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('total').textContent = '$' + (subtotal + 10).toFixed(2);
    };

    const updateRow = (row, qty, res = null) => {
    const priceText = row.querySelector('.price').textContent.replace('$', '');
    const price = parseFloat(priceText);
    row.querySelector('.qty-input').value = qty;
    row.querySelector('.item-total').textContent = '$' + (price * qty).toFixed(2);
    row.querySelector('.decrement-btn').disabled = qty <= 1;

    // Cập nhật subtotal và total từ server (nếu có)
    if (res && res.subtotal) {
        document.getElementById('subtotal').textContent = '$' + res.subtotal;
        document.getElementById('total').textContent = '$' + (parseFloat(res.subtotal) + 10).toFixed(2);
    } else {
        updateSummary();
    }

    // Cập nhật badge số lượng
    const badge = document.querySelector('.cart-count');
    if (badge && res && res.totalItems !== undefined) {
        badge.textContent = res.totalItems;
    }
};

    const handleRemove = (row) => {
        row.remove();
        updateSummary();
        if (!document.querySelector('#cart-items tr[data-id]')) {
            document.getElementById('cart-items').innerHTML = `
                <tr id="empty-cart">
                    <td colspan="6" class="text-center py-4">Giỏ hàng trống</td>
                </tr>`;
        }
    };

    const rowPayload = (row) => ({
        quantity: parseInt(row.querySelector('.qty-input').value) || 1,
        size: row.querySelector('.size-select')?.value || null,
        color: row.querySelector('.color-select')?.value || null,
    });

    const updateCartItem = (row) => {
        const id = row.dataset.id;

        fetch(`${baseUrl}/cart/update/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(rowPayload(row))
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                updateRow(row, res.quantity, res);
            } else {
                alert('Cập nhật thất bại!');
                location.reload();
            }
        })
        .catch(() => {
            alert('Lỗi kết nối!');
            location.reload();
        });
    };

  
    document.querySelectorAll('.increment-btn').forEach(btn => {
        btn.onclick = function () {
            const row = this.closest('tr');
            const id = row.dataset.id;
            fetch(`${baseUrl}/cart/increment/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(res => res.success && updateRow(row, res.quantity, res));
        };
    });

    document.querySelectorAll('.decrement-btn').forEach(btn => {
        btn.onclick = function () {
            const row = this.closest('tr');
            const id = row.dataset.id;
            fetch(`${baseUrl}/cart/decrement/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(res => res.success && updateRow(row, res.quantity, res));
        };
    });

 
    document.querySelectorAll('.qty-input').forEach(input => {
        let timeout;
        input.addEventListener('input', function () {
            clearTimeout(timeout);
            const row = this.closest('tr');
            const id = row.dataset.id;
            const qty = parseInt(this.value);

            if (isNaN(qty) || qty < 1) {
                this.value = 1;
                return;
            }

            timeout = setTimeout(() => {
                fetch(`${baseUrl}/cart/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(rowPayload(row))
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        updateRow(row, res.quantity, res);
                    } else {
                        alert('Cập nhật thất bại!');
                        location.reload();
                    }
                })
                .catch(() => {
                    alert('Lỗi kết nối!');
                    location.reload();
                });
            }, 600); 
        });
    });


    document.querySelectorAll('.size-select, .color-select').forEach(select => {
        select.addEventListener('change', function () {
            updateCartItem(this.closest('tr'));
        });
    });

    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.onclick = function () {
            if (!confirm('Xóa?')) return;
            const row = this.closest('tr');
            const id = row.dataset.id;
            fetch(`${baseUrl}/cart/remove/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(res => res.success && handleRemove(row));
        };
    });
});
</script>
@endsection
