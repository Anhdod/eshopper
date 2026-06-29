<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private array $statuses = ['pending', 'processing', 'completed', 'cancelled'];

    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->withCount('items')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => $this->statuses,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => $this->statuses,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', $this->statuses),
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Cap nhat trang thai don hang thanh cong!');
    }
}
