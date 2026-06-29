<!-- Topbar Start -->
<div class="container-fluid">
    <div class="row bg-secondary py-2 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark" href="">FAQs</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Help</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Support</a>
            </div>
        </div>
        <div class="col-lg-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark px-2" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-twitter"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-instagram"></i></a>
                <a class="text-dark pl-2" href=""><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                </h1>
            </a>
        </div>
        <div class="col-lg-6 col-6 text-left">
            <form action="{{ route('shop') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Search for products">
                    <div class="input-group-append">
                        <button class="input-group-text bg-transparent text-primary border-left-0" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-6 text-right">
            <a href="{{ auth()->check() ? route('wishlist') : route('login') }}" class="btn border">
                <i class="fas fa-heart text-primary"></i>
                <span class="badge">{{ auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0 }}</span>
            </a>
            <a href="{{ route('cart') }}" class="btn border">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge cart-count">
                    {{ Auth::check()
                        ? \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity')
                        : \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity')
                    }}
                </span>
            </a>
        </div>
    </div>
</div>
<!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid mb-5">
    <div class="row border-top px-xl-5">
        <!-- Categories Sidebar -->
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100"
               data-toggle="collapse" href="#navbar-vertical"
               style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0"
                 id="navbar-vertical">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    @foreach($categories as $category)
                        <div class="nav-item dropdown">
                            <a href="{{ $category->children->isEmpty() ? route('shop', ['category' => $category->id]) : '#' }}"
                               class="nav-link"
                               data-toggle="{{ $category->children->isNotEmpty() ? 'dropdown' : '' }}">
                                {{ $category->name }}
                                @if($category->children->isNotEmpty())
                                    <i class="fa fa-angle-down float-right mt-1"></i>
                                @endif
                            </a>
                            @if($category->children->isNotEmpty())
                                <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                                    @foreach($category->children as $child)
                                        <a href="{{ route('shop', ['category' => $child->id]) }}"
                                           class="dropdown-item">{{ $child->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </nav>
        </div>

        <!-- Main Navbar -->
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="{{ route('home') }}" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold">
                        <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                    </h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <!-- Dynamic Main Menu -->
                    <div class="navbar-nav mr-auto py-0">
                        @foreach ($menus as $menu)
                            @if ($menu->children->count() > 0)
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle {{ $menu->route && request()->routeIs($menu->route . '*') ? 'active' : '' }}" data-toggle="dropdown">
                                        {{ $menu->name }}
                                    </a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        @foreach ($menu->children as $child)
                                            @continue($child->route === 'shopdetail')
                                            <a href="{{ $child->route && Route::has($child->route) ? route($child->route) : '#' }}" class="dropdown-item">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                @continue($menu->route === 'shopdetail')
                                <a href="{{ $menu->route && Route::has($menu->route) ? route($menu->route) : '#' }}" class="nav-item nav-link {{ $menu->route && request()->routeIs($menu->route) ? 'active' : '' }}">
                                    {{ $menu->name }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                                <div class="col-lg-3 col-6 text-right">
                    <a href="{{ auth()->check() ? route('wishlist') : route('login') }}" class="btn border">
                        <i class="fas fa-heart text-primary"></i>
                        <span class="badge">{{ auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : 0 }}</span>
                    </a>
                    @if (auth()->check())
                        <a href="{{ route('cart') }}" class="btn border">
                            <i class="fas fa-shopping-cart text-primary"></i>
                           <span class="badge cart-count">
                            {{ Auth::check() 
                                ? \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity')
                                : \App\Models\CartItem::where('session_id', session()->getId())->sum('quantity') 
                            }}
                        </span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-link">Profile</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-link">Orders</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-link">Register</a>
                    @endif
                </div>
                </div>
            </nav>

            <!-- Banner / Carousel -->
            @if(isset($active) && $active == 'home')
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="{{ asset('img/carousel-1.jpg') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Fashionable Dress</h3>
                                    <a href="{{ route('shop') }}" class="btn btn-light py-2 px-3">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="{{ asset('img/carousel-2.jpg') }}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Reasonable Price</h3>
                                    <a href="{{ route('shop') }}" class="btn btn-light py-2 px-3">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
            @else
                <div class="container-fluid bg-secondary mb-5">
                    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
                        @isset($banner)
                            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{ $banner[0] }}</h1>
                            <div class="d-inline-flex">
                                <p class="m-0"><a href="{{ route('home') }}">Home</a></p>
                                <p class="m-0 px-2">-</p>
                                <p class="m-0">{{ $banner[1] }}</p>
                            </div>
                        @endisset
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Navbar End -->
