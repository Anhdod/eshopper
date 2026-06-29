<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('product', 'user')
            ->when($request->filled('rating'), fn ($query) => $query->where('rating', $request->rating))
            ->when($request->filled('status'), fn ($query) => $query->where('is_approved', $request->status === 'approved'))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = '%' . $request->q . '%';
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', $q)
                        ->orWhere('email', 'like', $q)
                        ->orWhere('message', 'like', $q);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateApproval(Request $request, Review $review)
    {
        $data = $request->validate([
            'is_approved' => 'required|boolean',
        ]);

        $review->update(['is_approved' => $data['is_approved']]);

        return back()->with('success', 'Cap nhat trang thai review thanh cong!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Da xoa review!');
    }
}
