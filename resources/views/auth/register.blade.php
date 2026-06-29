@extends('layouts.main')
@section('title', 'Đăng Ký')

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card border-0 mb-4">
                <div class="card-header bg-success border-0 text-center p-4">
                    <h1 class="font-weight-semi-bold m-0 text-white">Đăng Ký Tài Khoản</h1>
                </div>
                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="input-group mb-3">
                            <input type="text" name="name" class="form-control p-4" 
                                   value="{{ old('name') }}" required 
                                   placeholder="Họ và tên">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-success border-0">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control p-4" 
                                   value="{{ old('email') }}" required 
                                   placeholder="Email của bạn">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-success border-0">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control p-4" 
                                   required placeholder="Mật khẩu (tối thiểu 6 ký tự)">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-success border-0">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-group mb-4">
                            <input type="password" name="password_confirmation" class="form-control p-4" 
                                   required placeholder="Nhập lại mật khẩu">
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent text-success border-0">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-success btn-block py-3 font-weight-bold">
                                ĐĂNG KÝ NGAY
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Đã có tài khoản?
                            <a href="{{ route('login') }}" class="text-success font-weight-bold">
                                Đăng nhập
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection