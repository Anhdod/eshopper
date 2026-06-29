@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Products</h5>
                <h3>{{ \App\Models\Product::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Categories</h5>
                <h3>{{ \App\Models\Category::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Orders</h5>
                <h3>{{ \App\Models\Order::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5>Unread contacts</h5>
                <h3>{{ \App\Models\Contact::where('is_read', false)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5>Reviews</h5>
                <h3>{{ \App\Models\Review::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h5>Newsletter</h5>
                <h3>{{ \App\Models\Newsletter::count() }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
