<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $from = $request->date('from')?->startOfDay();
        $to = $request->date('to')?->endOfDay();

        $ordersQuery = Order::query()
            ->when($from, fn ($query) => $query->where('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->where('created_at', '<=', $to));

        $completedQuery = (clone $ordersQuery)->where('status', 'completed');

        $summary = [
            'orders' => (clone $ordersQuery)->count(),
            'completed_orders' => (clone $completedQuery)->count(),
            'revenue' => (clone $completedQuery)->sum('total'),
            'discounts' => (clone $completedQuery)->sum('discount'),
            'average_order' => (clone $completedQuery)->avg('total') ?? 0,
            'low_stock' => Product::whereNotNull('stock')->where('stock', '<=', 5)->count(),
        ];

        $recentOrders = (clone $ordersQuery)->with('user')->latest()->take(10)->get();

        $topProducts = OrderItem::selectRaw('product_id, product_name, SUM(quantity) as sold_qty, SUM(total) as revenue')
            ->whereHas('order', function ($query) use ($from, $to) {
                $query->where('status', 'completed')
                    ->when($from, fn ($orderQuery) => $orderQuery->where('created_at', '>=', $from))
                    ->when($to, fn ($orderQuery) => $orderQuery->where('created_at', '<=', $to));
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('sold_qty')
            ->take(10)
            ->get();

        return view('admin.reports.revenue', compact('summary', 'recentOrders', 'topProducts'));
    }
}
