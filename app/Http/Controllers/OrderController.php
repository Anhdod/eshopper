<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', [
            'active' => 'orders',
            'banner' => ['MY ORDERS', 'Orders'],
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return view('orders.show', [
            'active' => 'orders',
            'banner' => ['ORDER DETAIL', $order->order_number],
            'order' => $order,
        ]);
    }
}
