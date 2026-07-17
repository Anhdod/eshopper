<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $category = null)
    {
        $query = Product::with('galleryImages');
        $cat = null;

        if ($category) {
            $cat = Category::find($category);
            if ($cat) {
                $query->where('category_id', $category);
            }
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('price') && str_contains($request->price, '-')) {
            [$min, $max] = explode('-', $request->price);
            $query->whereBetween('price', [$min, $max]);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->filled('color')) {
            $query->whereJsonContains('color', $request->color);
        }

        if ($request->boolean('in_stock')) {
            $query->where(function ($stockQuery) {
                $stockQuery->whereNull('stock')->orWhere('stock', '>', 0);
            });
        }

        match ($request->get('sort')) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'newest' => $query->latest(),
            default => $query->latest(),
        };

        $totalProducts = $query->count();
        $priceRanges = [
            ['min' => 0, 'max' => 100, 'count' => Product::whereBetween('price', [0, 100])->count()],
            ['min' => 100, 'max' => 200, 'count' => Product::whereBetween('price', [100, 200])->count()],
            ['min' => 200, 'max' => 300, 'count' => Product::whereBetween('price', [200, 300])->count()],
            ['min' => 300, 'max' => 400, 'count' => Product::whereBetween('price', [300, 400])->count()],
            ['min' => 400, 'max' => 500, 'count' => Product::whereBetween('price', [400, 500])->count()],
        ];
        $colors = Product::whereNotNull('color')
            ->get()
            ->flatMap(fn ($product) => $product->color ?? [])
            ->countBy()
            ->toArray();

        return view('shop', [
            'active' => 'shop',
            'banner' => ['OUR SHOP', $cat->name ?? 'All Products'],
            'products' => $query->paginate(9),
            'totalProducts' => $totalProducts,
            'priceRanges' => $priceRanges,
            'colors' => $colors,
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();
        $reviews = $product->reviews()->where('is_approved', true)->latest()->get();

        return view('shopdetail', [
            'active' => 'shopdetail',
            'banner' => ['SHOP DETAIL', $product->name],
            'product' => $product,
            'relatedProducts' => $related,
            'reviews' => $reviews,
        ]);
    }
}
