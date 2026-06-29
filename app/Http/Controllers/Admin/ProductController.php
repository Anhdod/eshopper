<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImage($request);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Them san pham thanh cong!');
    }

    public function show(Product $product)
    {
        return redirect()->route('shopdetail', $product);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $data['image'] = $this->saveImage($request);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cap nhat thanh cong!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->deleteImage($product);
        $product->delete();

        return back()->with('success', 'Xoa san pham thanh cong!');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:255',
            'sizes' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['color'] = $this->splitList($request->color);
        $data['sizes'] = $this->splitList($request->sizes);

        unset($data['image']);

        return $data;
    }

    private function saveImage(Request $request): string
    {
        $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $request->file('image')->getClientOriginalName());
        $request->file('image')->move(public_path('img'), $imageName);

        return $imageName;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image && file_exists(public_path('img/' . $product->image))) {
            @unlink(public_path('img/' . $product->image));
        }
    }

    private function splitList(?string $value): array
    {
        return collect(explode(',', $value ?? ''))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
