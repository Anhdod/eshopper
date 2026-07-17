@extends('admin.layout')
@section('title', 'Edit Coupon')

@section('content')
<h3>Edit coupon</h3>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
    @method('PUT')
    @include('admin.coupons.form')
</form>
@endsection
