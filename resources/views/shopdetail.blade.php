@extends('layouts.main')
@section('content')
   <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        @php
                            // Giả sử bạn có nhiều ảnh, hoặc chỉ 1 ảnh
                            $images = $product->images ?? [$product->image];
                        @endphp
                        @foreach($images as $index => $img)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img class="w-100 h-100" src="{{ asset('img/' . $img) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $product->name }}</h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            <small class="fa{{ $i <= 4 ? 's' : 'r' }} fa-star{{ $i == 4.5 ? '-half-alt' : '' }}"></small>
                        @endfor
                    </div>
                    <small class="pt-1">({{ $reviews->count() }} Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">
                    ${{ number_format($product->price, 2) }}
                    @if($product->original_price)
                        <del class="text-muted ml-2">${{ number_format($product->original_price, 2) }}</del>
                    @endif
                </h3>
                <p class="mb-4">
                    {{ $product->description ?? 'Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea...' }}
                </p>

               <!-- Sizes (nếu có trong DB) -->
                @if(!empty($product->sizes))
                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                    <div>
                        @foreach($product->sizes as $size)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" form="add-cart-form" class="custom-control-input" id="size-{{ $loop->index }}" name="size" value="{{ $size }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="custom-control-label" for="size-{{ $loop->index }}">{{ $size }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Colors (nếu có) -->
                @if(!empty($product->color))
                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                    <div>
                        @foreach($product->color as $color)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" form="add-cart-form" class="custom-control-input" id="color-{{ $loop->index }}" name="color" value="{{ $color }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="custom-control-label" for="color-{{ $loop->index }}">{{ ucfirst($color) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity + Add to Cart -->
                <form id="add-cart-form" action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center mb-4 pt-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-minus">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" name="quantity" class="form-control bg-secondary text-center" value="1" min="1">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="fa fa-shopping-cart mr-1"></i> Add To Cart
                    </button>
                </form>
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary px-3">
                            <i class="fas fa-heart mr-1"></i> Add To Wishlist
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 mb-4">
                        <i class="fas fa-heart mr-1"></i> Login To Wishlist
                    </a>
                @endauth

                <!-- Share -->
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-twitter"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs: Description, Info, Reviews -->
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Information</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews ({{ $reviews->count() }})</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        <p>{{ $product->description ?? 'No description available.' }}</p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Additional Information</h4>
                        <p>Product ID: {{ $product->id }} | Category: {{ $product->category->name ?? 'N/A' }}</p>
                        <!-- Có thể thêm trọng lượng, kích thước, v.v. -->
                    </div>
                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">{{ $reviews->count() }} reviews for "{{ $product->name }}"</h4>
                                @forelse($reviews as $review)
                                    <div class="media mb-4">
                                        <div class="media-body">
                                            <h6>{{ $review->name }}<small class="text-muted"> - {{ $review->created_at->format('d/m/Y') }}</small></h6>
                                            <div class="text-primary mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <small class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></small>
                                                @endfor
                                            </div>
                                            <p>{{ $review->message }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Be the first to review this product!</p>
                                @endforelse
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                                <small>Your email address will not be published. Required fields are marked *</small>
                                <form action="{{ route('reviews.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="rating">Your Rating *</label>
                                        <select id="rating" name="rating" class="form-control" required>
                                            <option value="5">5</option>
                                            <option value="4">4</option>
                                            <option value="3">3</option>
                                            <option value="2">2</option>
                                            <option value="1">1</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" name="message" cols="30" rows="5" class="form-control" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ auth()->user()->name ?? old('name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" name="email" class="form-control" id="email" value="{{ auth()->user()->email ?? old('email') }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->

    <!-- Related Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach($relatedProducts as $related)
                        <div class="card product-item border-0">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="{{ asset('img/' . $related->image) }}" alt="{{ $related->name }}">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">{{ $related->name }}</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>${{ number_format($related->price, 2) }}</h6>
                                    @if($related->original_price)
                                        <h6 class="text-muted ml-2"><del>${{ number_format($related->original_price, 2) }}</del></h6>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="{{ route('shopdetail', $related->id) }}" class="btn btn-sm text-dark p-0">
                                    <i class="fas fa-eye text-primary mr-1"></i>View Detail
                                </a>
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $related->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm text-dark p-0">
                                        <i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Related Products End -->
@endsection
