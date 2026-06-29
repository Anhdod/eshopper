<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact', [
            'active' => 'contact',
            'banner' => ['CONTACT US', 'Contact'],
        ]);
    }

    public function store(Request $request)
    {
        Contact::create($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]));

        return back()->with('success', 'Cam on ban da lien he. Chung toi se phan hoi som!');
    }
}
