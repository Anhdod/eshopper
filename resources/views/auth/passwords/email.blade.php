@extends('layouts.main')
@section('title', 'Forgot Password')

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0">
                <div class="card-header bg-secondary border-0 p-4">
                    <h4 class="font-weight-semi-bold m-0">Forgot password</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>
                        <button class="btn btn-primary btn-block">Send reset link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
