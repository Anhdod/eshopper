<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create', ['coupon' => new Coupon()]);
    }

    public function store(Request $request)
    {
        Coupon::create($this->validatedData($request));

        return redirect()->route('admin.coupons.index')->with('success', 'Them ma giam gia thanh cong!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validatedData($request, $coupon));

        return redirect()->route('admin.coupons.index')->with('success', 'Cap nhat ma giam gia thanh cong!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('success', 'Xoa ma giam gia thanh cong!');
    }

    private function validatedData(Request $request, ?Coupon $coupon = null): array
    {
        $id = $coupon?->id ?? 'NULL';
        $data = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ]);

        $data['code'] = Str::upper(trim($data['code']));
        $data['minimum_order'] = $data['minimum_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
