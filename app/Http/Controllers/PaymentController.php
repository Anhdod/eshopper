<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        return view('payment', [
            'order' => $order,
            'active' => 'checkout',
            'banner' => ['PAYMENT', $order->order_number],
        ]);
    }

    public function complete(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => 'PAY-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                'paid_at' => now(),
                'status' => 'processing',
            ]);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Thanh toan thanh cong!');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === Auth::id(), 403);
    }
}
