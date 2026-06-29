@extends('layouts.main')
@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-3 col-md-12">
            <!-- Price Filter Start -->
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                <form method="GET" action="{{ route('shop', request()->route('category')) }}" id="price-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('color'))
                        <input type="hidden" name="color" value="{{ request('color') }}">
                    @endif
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input price-filter" id="price-all" {{ !request('price') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="price-all">All Price</label>
                        <span class="badge border font-weight-normal">{{ $totalProducts }}</span>
                    </div>
                    @foreach($priceRanges as $range)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" name="price" class="custom-control-input price-filter" id="price-{{ $loop->index }}" value="{{ $range['min'] }}-{{ $range['max'] }}"
                                   {{ request('price') == $range['min'].'-'.$range['max'] ? 'checked' : '' }}>
                            <label class="custom-control-label" for="price-{{ $loop->index }}">${{ $range['min'] }} - ${{ $range['max'] }}</label>
                            <span class="badge border font-weight-normal">{{ $range['count'] }}</span>
                        </div>
                    @endforeach
                </form>
            </div>
            <!-- Price End -->

            <!-- Color Filter Start -->
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by color</h5>
                <form method="GET" action="{{ route('shop', request()->route('category')) }}" id="color-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('price'))
                        <input type="hidden" name="price" value="{{ request('price') }}">
                    @endif
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input color-filter" id="color-all" {{ !request('color') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="color-all">All Color</label>
                        <span class="badge border font-weight-normal">{{ $totalProducts }}</span>
                    </div>
                    @foreach($colors as $color => $count)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" name="color" class="custom-control-input color-filter" id="color-{{ $loop->index }}" value="{{ $color }}"
                                   {{ request('color') == $color ? 'checked' : '' }}>
                            <label class="custom-control-label" for="color-{{ $loop->index }}">{{ ucfirst($color) }}</label>
                            <span class="badge border font-weight-normal">{{ $count }}</span>
                        </div>
                    @endforeach
                </form>
            </div>
            <!-- Color End -->
        </div>
        <!-- Shop Sidebar End -->

        <!-- Shop Product Start -->
        <div class="col-lg-9 col-md-12">
            <div class="row pb-3">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="{{ asset('img/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">{{ $product->name }}</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>${{ number_format($product->price, 2) }}</h6>
                                    @if($product->original_price)
                                        <h6 class="text-muted ml-2"><del>${{ number_format($product->original_price, 2) }}</del></h6>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="{{ route('shopdetail', $product->id) }}" class="btn btn-sm text-dark p-0">
                                    View Detail
                                </a>
                                @auth
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm text-dark p-0">
                                            <i class="fas fa-heart text-primary"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-heart text-primary"></i>
                                    </a>
                                @endauth
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm text-dark p-0">
                                            Add To Cart
                                        </button>
                                    </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No products found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="col-12 pb-1">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
        <!-- Shop Product End -->
    </div>
</div>

{{-- JavaScript for Filter --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Price Filter
    document.querySelectorAll('.price-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            document.querySelectorAll('.price-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            document.getElementById('price-form').submit();
        });
    });

    // Color Filter
    document.querySelectorAll('.color-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            document.querySelectorAll('.color-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            document.getElementById('color-form').submit();
        });
    });

    // Uncheck "All" when others are checked
    document.querySelectorAll('.price-filter, .color-filter').forEach(checkbox => {
        if (checkbox.id.includes('-all')) {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    const formId = this.id.includes('price') ? 'price-form' : 'color-form';
                    document.querySelectorAll(`#${formId} .custom-control-input:not(#${this.id})`).forEach(cb => cb.checked = false);
                    document.getElementById(formId).submit();
                }
            });
        }
    });
});
</script>
@endsection
