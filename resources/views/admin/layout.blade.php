<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="d-flex">
        <div class="bg-dark text-white vh-100 p-3 position-sticky top-0" style="width: 250px;">
            <h4 class="text-center">ADMIN PANEL</h4>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-white"><i class="bi bi-house"></i> Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('admin.products.index') }}" class="nav-link text-white"><i class="bi bi-box"></i> Products</a></li>
                <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link text-white"><i class="bi bi-receipt"></i> Orders</a></li>
                <li class="nav-item"><a href="{{ route('admin.coupons.index') }}" class="nav-link text-white"><i class="bi bi-ticket-perforated"></i> Coupons</a></li>
                <li class="nav-item"><a href="{{ route('admin.reports.revenue') }}" class="nav-link text-white"><i class="bi bi-graph-up"></i> Revenue</a></li>
                <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link text-white"><i class="bi bi-tags"></i> Categories</a></li>
                <li class="nav-item"><a href="{{ route('admin.menus.index') }}" class="nav-link text-white"><i class="bi bi-list"></i> Menus</a></li>
                <li class="nav-item"><a href="{{ route('admin.contacts.index') }}" class="nav-link text-white"><i class="bi bi-envelope"></i> Contacts</a></li>
                <li class="nav-item"><a href="{{ route('admin.reviews.index') }}" class="nav-link text-white"><i class="bi bi-star"></i> Reviews</a></li>
                <li class="nav-item"><a href="{{ route('admin.newsletters.index') }}" class="nav-link text-white"><i class="bi bi-newspaper"></i> Newsletter</a></li>
                <li class="nav-item"><a href="{{ url('/') }}" class="nav-link text-white"><i class="bi bi-arrow-left-circle"></i> Ve trang chu</a></li>
            </ul>
        </div>

        <div class="flex-grow-1 p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
