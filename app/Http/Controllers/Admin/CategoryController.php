<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        Category::create($this->validatedData($request));

        return redirect()->route('admin.categories.index')->with('success', 'Them danh muc thanh cong!');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->whereKeyNot($category->id)->orderBy('name')->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('admin.categories.index')->with('success', 'Cap nhat danh muc thanh cong!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Da xoa danh muc!');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($category && (int) $value === (int) $category->id) {
                        $fail('Danh muc cha khong hop le.');
                    }
                },
            ],
        ]);
    }
}
