@extends('layouts.main')
@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div class="mb-4">
                <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                <form id="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input name="first_name" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input name="last_name" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail <span class="text-danger">*</span></label>
                            <input name="email" class="form-control" type="email" value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No <span class="text-danger">*</span></label>
                            <input name="phone" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 1 <span class="text-danger">*</span></label>
                            <input name="address1" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 2</label>
                            <input name="address2" class="form-control" type="text">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country <span class="text-danger">*</span></label>
                            <select name="country" class="custom-select" required>
                                <option value="United States">United States</option>
                                <option value="Vietnam">Vietnam</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>City <span class="text-danger">*</span></label>
                            <input name="city" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>State <span class="text-danger">*</span></label>
                            <input name="state" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code <span class="text-danger">*</span></label>
                            <input name="zip" class="form-control" type="text" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="shipto" name="ship_to_different">
                                <label class="custom-control-label" for="shipto" data-toggle="collapse" data-target="#shipping-address">Ship to different address</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="collapse mb-4" id="shipping-address">
                <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                <div class="row">
                    @foreach([
                        'ship_first_name' => 'First Name',
                        'ship_last_name' => 'Last Name',
                        'ship_email' => 'E-mail',
                        'ship_phone' => 'Mobile No',
                        'ship_address1' => 'Address Line 1',
                        'ship_address2' => 'Address Line 2',
                        'ship_city' => 'City',
                        'ship_state' => 'State',
                        'ship_zip' => 'ZIP Code',
                    ] as $field => $label)
                        <div class="col-md-6 form-group">
                            <label>{{ $label }}</label>
                            <input name="{{ $field }}" form="checkout-form" class="form-control" type="{{ $field === 'ship_email' ? 'email' : 'text' }}">
                        </div>
                    @endforeach
                    <div class="col-md-6 form-group">
                        <label>Country</label>
                        <select name="ship_country" form="checkout-form" class="custom-select">
                            <option value="United States">United States</option>
                            <option value="Vietnam">Vietnam</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                </div>
                <div class="card-body">
                    <h5 class="font-weight-medium mb-3">Products</h5>
                    @forelse($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <p class="mb-0">{{ $item->product->name }} <small class="text-muted">x{{ $item->quantity }}</small></p>
                            <p class="mb-0">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    @empty
                        <p class="text-center text-muted">Gio hang trong</p>
                    @endforelse
                    <hr class="mt-2">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="font-weight-medium">Subtotal</h6>
                        <h6 class="font-weight-medium">${{ number_format($subtotal, 2) }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">${{ number_format($shipping, 2) }}</h6>
                    </div>
                    @if(($discount ?? 0) > 0)
                        <div class="d-flex justify-content-between mt-2">
                            <h6 class="font-weight-medium">Discount {{ $coupon ? '(' . $coupon->code . ')' : '' }}</h6>
                            <h6 class="font-weight-medium text-success">-${{ number_format($discount, 2) }}</h6>
                        </div>
                    @endif
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <form action="{{ route('checkout.coupon') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code" value="{{ $coupon->code ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                    </form>
                    @if($coupon ?? null)
                        <form action="{{ route('checkout.coupon.remove') }}" method="POST" class="mb-3">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove coupon</button>
                        </form>
                    @endif
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold">${{ number_format($total, 2) }}</h5>
                    </div>
                </div>
            </div>

            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Payment</h4>
                </div>
                <div class="card-body">
                    @foreach([
                        'cod' => 'Cash on Delivery',
                        'online' => 'Online Payment',
                        'paypal' => 'Paypal',
                        'directcheck' => 'Direct Check',
                        'banktransfer' => 'Bank Transfer',
                    ] as $value => $label)
                        <div class="form-group {{ $loop->last ? 'mb-0' : '' }}">
                            <div class="custom-control custom-radio">
                                <input type="radio" form="checkout-form" class="custom-control-input" name="payment" id="payment-{{ $value }}" value="{{ $value }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="custom-control-label" for="payment-{{ $value }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <button type="submit" form="checkout-form" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place Order</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
