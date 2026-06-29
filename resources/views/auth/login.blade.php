@extends('layouts.main')
@section('title', 'Đăng Nhập')

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-12">
            <div class="card border-0 mb-4">
                <div class="card-header bg-secondary border-0 text-center p-4">
                    <h1 class="font-weight-semi-bold m-0 text-white">Đăng Nhập</h1>
                </div>
                <div class="card-body p-4">

                    <!-- Hiển thị lỗi từ session hoặc validate -->
                    @if (session('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control p-4" 
                                   value="{{ old('email') }}" required autofocus 
                                   placeholder="Nhập email của bạn">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-primary border-0">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="input-group mb-4">
                            <input type="password" name="password" class="form-control p-4" 
                                   required placeholder="Nhập mật khẩu">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-primary border-0">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Remember & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label text-dark">Ghi nhớ đăng nhập</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-primary small">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold">
                                ĐĂNG NHẬP
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Chưa có tài khoản?
                            <a href="{{ route('register') }}" class="text-primary font-weight-bold">
                                Đăng ký ngay
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
