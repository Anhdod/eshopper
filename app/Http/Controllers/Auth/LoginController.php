<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
     
        $this->mergeCartToUser();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard'); 
        }

        return redirect()->intended(route('home'));
    }

    return back()->withErrors(['email' => 'Thông tin đăng nhập sai!']);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

   protected function mergeCartToUser()
{
    $sessionId = session()->getId();
    $userId = Auth::id();

    $sessionItems = \App\Models\CartItem::where('session_id', $sessionId)
        ->whereNull('user_id') 
        ->get();

    foreach ($sessionItems as $item) {
        $userItem = \App\Models\CartItem::firstOrNew([
            'user_id' => $userId,
            'product_id' => $item->product_id,
            'color' => $item->color,
            'size' => $item->size,
        ]);

        $userItem->quantity = ($userItem->quantity ?? 0) + $item->quantity;
        $userItem->session_id = null; 
        $userItem->save();

        $item->delete();
    }
}
}
