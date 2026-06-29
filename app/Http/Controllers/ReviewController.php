<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:5000',
        ]);

        $data['user_id'] = Auth::id();
        $data['is_approved'] = false;
        $product->reviews()->create($data);

        return back()->with('success', 'Cam on ban da danh gia san pham! Review se hien thi sau khi duoc duyet.');
    }
}
