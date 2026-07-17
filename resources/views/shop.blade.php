@extends('layouts.main')
@section('content')
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-3 col-md-12">
            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                <form method="GET" action="{{ route('shop', request()->route('category')) }}" id="price-form">
                    @foreach(['q', 'color', 'sort', 'min_price', 'max_price', 'in_stock'] as $field)
                        @if(request($field))
                            <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
                        @endif
                    @endforeach
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input price-filter" id="price-all" {{ !request('price') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="price-all">All Price</label>
                        <span class="badge border font-weight-normal">{{ $totalProducts }}</span>
                    </div>
                    @foreach($priceRanges as $range)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" name="price" class="custom-control-input price-filter" id="price-{{ $loop->index }}" value="{{ $range['min'] }}-{{ $range['max'] }}" {{ request('price') == $range['min'].'-'.$range['max'] ? 'checked' : '' }}>
                            <label class="custom-control-label" for="price-{{ $loop->index }}">${{ $range['min'] }} - ${{ $range['max'] }}</label>
                            <span class="badge border font-weight-normal">{{ $range['count'] }}</span>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="border-bottom mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by color</h5>
                <form method="GET" action="{{ route('shop', request()->route('category')) }}" id="color-form">
                    @foreach(['q', 'price', 'sort', 'min_price', 'max_price', 'in_stock'] as $field)
                        @if(request($field))
                            <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
                        @endif
                    @endforeach
                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                        <input type="checkbox" class="custom-control-input color-filter" id="color-all" {{ !request('color') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="color-all">All Color</label>
                        <span class="badge border font-weight-normal">{{ $totalProducts }}</span>
                    </div>
                    @foreach($colors as $color => $count)
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" name="color" class="custom-control-input color-filter" id="color-{{ $loop->index }}" value="{{ $color }}" {{ request('color') == $color ? 'checked' : '' }}>
                            <label class="custom-control-label" for="color-{{ $loop->index }}">{{ ucfirst($color) }}</label>
                            <span class="badge border font-weight-normal">{{ $count }}</span>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        <div class="col-lg-9 col-md-12">
            <form method="GET" action="{{ route('shop', request()->route('category')) }}" class="row align-items-end mb-4">
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search products...">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Min price</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" min="0" step="0.01">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Max price</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" min="0" step="0.01">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Sort</label>
                    <select name="sort" class="form-control">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price low</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price high</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" name="in_stock" value="1" class="custom-control-input" id="in-stock" {{ request()->boolean('in_stock') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="in-stock">In stock</label>
                    </div>
                    @foreach(['price', 'color'] as $field)
                        @if(request($field))
                            <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
                        @endif
                    @endforeach
                    <button class="btn btn-primary btn-block">Apply</button>
                </div>
            </form>

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
                                <small class="{{ $product->stock !== null && $product->stock <= 5 ? 'text-danger' : 'text-muted' }}">
                                    {{ $product->stock === null ? 'Available' : ($product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock') }}
                                </small>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="{{ route('shopdetail', $product->id) }}" class="btn btn-sm text-dark p-0">View Detail</a>
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
                                    <button type="submit" class="btn btn-sm text-dark p-0" {{ $product->stock !== null && $product->stock <= 0 ? 'disabled' : '' }}>
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

            <div class="col-12 pb-1">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.price-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            document.querySelectorAll('.price-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            document.getElementById('price-form').submit();
        });
    });

    document.querySelectorAll('.color-filter').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            document.querySelectorAll('.color-filter').forEach(cb => {
                if (cb !== this) cb.checked = false;
            });
            document.getElementById('color-form').submit();
        });
    });
});
</script>
@endsection
