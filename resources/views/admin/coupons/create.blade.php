@extends('admin.layout')
@section('title', 'Create Coupon')

@section('content')
<h3>Create coupon</h3>

<form action="{{ route('admin.coupons.store') }}" method="POST">
    @include('admin.coupons.form')
</form>
@endsection
