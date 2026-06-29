<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('auth.profile', [
            'user' => $request->user(),
            'active' => 'profile',
            'banner' => ['PROFILE', 'Profile'],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return back()->with('success', 'Cap nhat profile thanh cong!');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Mat khau hien tai khong dung.']);
        }

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Doi mat khau thanh cong!');
    }
}
