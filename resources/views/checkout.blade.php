{{-- resources/views/checkout.blade.php --}}

@extends('layouts.main')
@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <!-- === Billing Address (giữ nguyên) === -->
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
                                <!-- Thêm các quốc gia khác -->
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
                                <label class="custom-control-label" for="shipto" data-toggle="collapse" data-target="#shipping-address">
                                    Ship to different address
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- === Shipping Address (collapse) === -->
            <div class="collapse mb-4" id="shipping-address">
                <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>First Name</label>
                        <input name="ship_first_name" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Last Name</label>
                        <input name="ship_last_name" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>E-mail</label>
                        <input name="ship_email" form="checkout-form" class="form-control" type="email">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mobile No</label>
                        <input name="ship_phone" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Address Line 1</label>
                        <input name="ship_address1" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Address Line 2</label>
                        <input name="ship_address2" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Country</label>
                        <select name="ship_country" form="checkout-form" class="custom-select">
                            <option value="United States">United States</option>
                            <option value="Vietnam">Vietnam</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>City</label>
                        <input name="ship_city" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>State</label>
                        <input name="ship_state" form="checkout-form" class="form-control" type="text">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>ZIP Code</label>
                        <input name="ship_zip" form="checkout-form" class="form-control" type="text">
                    </div>
                </div>
            </div>
        </div>

        <!-- === Order Summary === -->
        <div class="col-lg-4">
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                </div>
                <div class="card-body">
                    <h5 class="font-weight-medium mb-3">Products</h5>
                    @forelse($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <p class="mb-0">
                                {{ $item->product->name }}
                                <small class="text-muted">x{{ $item->quantity }}</small>
                            </p>
                            <p class="mb-0">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    @empty
                        <p class="text-center text-muted">Giỏ hàng trống</p>
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
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold">${{ number_format($total, 2) }}</h5>
                    </div>
                </div>
            </div>

            <!-- === Payment Method === -->
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Payment</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" form="checkout-form" class="custom-control-input" name="payment" id="paypal" value="paypal" checked>
                            <label class="custom-control-label" for="paypal">Paypal</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" form="checkout-form" class="custom-control-input" name="payment" id="directcheck" value="directcheck">
                            <label class="custom-control-label" for="directcheck">Direct Check</label>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <div class="custom-control custom-radio">
                            <input type="radio" form="checkout-form" class="custom-control-input" name="payment" id="banktransfer" value="banktransfer">
                            <label class="custom-control-label" for="banktransfer">Bank Transfer</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <button type="submit" form="checkout-form" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
