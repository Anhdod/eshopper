<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
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
        $coupon = $this->couponFromSession($subtotal);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0;

        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'coupon' => $coupon,
            'total' => $subtotal + $shipping - $discount,
            'active' => 'checkout',
            'banner' => ['CHECKOUT', 'Checkout'],
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate([
            'coupon_code' => 'required|string|max:255',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $coupon = Coupon::where('code', strtoupper(trim($data['coupon_code'])))->first();

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            session()->forget('coupon_code');

            return back()->withErrors(['coupon_code' => 'Ma giam gia khong hop le hoac khong du dieu kien.']);
        }

        session(['coupon_code' => $coupon->code]);

        return back()->with('success', 'Da ap dung ma giam gia.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');

        return back()->with('success', 'Da bo ma giam gia.');
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
            'payment' => 'required|string|in:cod,paypal,directcheck,banktransfer,online',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Gio hang cua ban dang trong!');
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shipping = 10.00;
        $coupon = $this->couponFromSession($subtotal);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0;

        $order = DB::transaction(function () use ($cartItems, $data, $subtotal, $shipping, $coupon, $discount) {
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
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $subtotal + $shipping - $discount,
                'payment_status' => in_array($data['payment'], ['paypal', 'online'], true) ? 'pending' : 'unpaid',
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
            session()->forget('coupon_code');

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $order;
        });

        if (in_array($order->payment_method, ['online', 'paypal'], true)) {
            return redirect()->route('payment.show', $order)->with('success', 'Dat hang thanh cong, vui long thanh toan de hoan tat don hang.');
        }

        return redirect()->route('home')->with('success', 'Dat hang thanh cong! Ma don hang: ' . $order->order_number);
    }

    private function couponFromSession(float $subtotal): ?Coupon
    {
        $code = session('coupon_code');

        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            session()->forget('coupon_code');

            return null;
        }

        return $coupon;
    }
}
