@extends('layouts.main')
@section('title', 'Profile')

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 mb-4">
                <div class="card-header bg-secondary border-0 p-4">
                    <h4 class="font-weight-semi-bold m-0">Profile</h4>
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

                    <form action="{{ route('profile.update') }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Update profile</button>
                    </form>

                    <hr>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Current password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm new password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-success">Change password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
