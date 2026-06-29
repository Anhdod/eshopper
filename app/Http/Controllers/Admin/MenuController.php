<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->orderBy('name')->paginate(20);

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->orderBy('order')->get();

        return view('admin.menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        Menu::create($this->validatedData($request));

        return redirect()->route('admin.menus.index')->with('success', 'Them menu thanh cong!');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')->whereKeyNot($menu->id)->orderBy('order')->get();

        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($this->validatedData($request, $menu));

        return redirect()->route('admin.menus.index')->with('success', 'Cap nhat menu thanh cong!');
    }

    public function destroy(Menu $menu)
    {
        Menu::where('parent_id', $menu->id)->update(['parent_id' => null]);
        $menu->delete();

        return back()->with('success', 'Da xoa menu!');
    }

    private function validatedData(Request $request, ?Menu $menu = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'type' => 'required|string|max:50',
            'parent_id' => [
                'nullable',
                'exists:menus,id',
                function ($attribute, $value, $fail) use ($menu) {
                    if ($menu && (int) $value === (int) $menu->id) {
                        $fail('Menu cha khong hop le.');
                    }
                },
            ],
            'order' => 'nullable|integer|min:0',
        ]);
    }
}
