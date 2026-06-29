<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function home()
    {
        $products = Product::latest()->take(8)->get();

        return view('home', [
            'active' => 'home',
            'products' => $products,
        ]);
    }
}
