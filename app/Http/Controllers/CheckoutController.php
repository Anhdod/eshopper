<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Gio hang cua ban dang trong!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shipping = 10.00;

        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
            'active' => 'checkout',
            'banner' => ['CHECKOUT', 'Checkout'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:30',
            'ship_to_different' => 'nullable',
            'ship_first_name' => 'nullable|string|max:255',
            'ship_last_name' => 'nullable|string|max:255',
            'ship_email' => 'nullable|email|max:255',
            'ship_phone' => 'nullable|string|max:50',
            'ship_address1' => 'nullable|string|max:255',
            'ship_address2' => 'nullable|string|max:255',
            'ship_country' => 'nullable|string|max:255',
            'ship_city' => 'nullable|string|max:255',
            'ship_state' => 'nullable|string|max:255',
            'ship_zip' => 'nullable|string|max:30',
            'payment' => 'required|string|in:paypal,directcheck,banktransfer',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Gio hang cua ban dang trong!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shipping = 10.00;

        $order = DB::transaction(function () use ($cartItems, $data, $subtotal, $shipping) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'DH-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address1' => $data['address1'],
                'address2' => $data['address2'] ?? null,
                'country' => $data['country'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'ship_to_different' => isset($data['ship_to_different']),
                'shipping_address' => isset($data['ship_to_different']) ? [
                    'first_name' => $data['ship_first_name'] ?? null,
                    'last_name' => $data['ship_last_name'] ?? null,
                    'email' => $data['ship_email'] ?? null,
                    'phone' => $data['ship_phone'] ?? null,
                    'address1' => $data['ship_address1'] ?? null,
                    'address2' => $data['ship_address2'] ?? null,
                    'country' => $data['ship_country'] ?? null,
                    'city' => $data['ship_city'] ?? null,
                    'state' => $data['ship_state'] ?? null,
                    'zip' => $data['ship_zip'] ?? null,
                ] : null,
                'payment_method' => $data['payment'],
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'color' => $item->color,
                    'size' => $item->size,
                    'total' => $item->product->price * $item->quantity,
                ]);

                if ($item->product->stock !== null) {
                    $item->product->decrement('stock', min($item->quantity, $item->product->stock));
                }
            }

            CartItem::where('user_id', Auth::id())->delete();

            return $order;
        });

        return redirect()->route('home')->with('success', 'Dat hang thanh cong! Ma don hang: ' . $order->order_number);
    }
}
