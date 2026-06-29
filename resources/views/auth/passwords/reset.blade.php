@extends('layouts.main')
@section('title', 'Reset Password')

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0">
                <div class="card-header bg-secondary border-0 p-4">
                    <h4 class="font-weight-semi-bold m-0">Reset password</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autofocus>
                        </div>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm new password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-primary btn-block">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
