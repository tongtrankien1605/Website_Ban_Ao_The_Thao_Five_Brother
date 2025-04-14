<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product', 'images'])->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_product' => 'required|exists:products,id',
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $review = Review::create([
            'id_user' => auth()->id(),
            'id_product' => $request->id_product,
            'content' => $request->content,
            'rating' => $request->rating
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                ReviewImage::create([
                    'id_review' => $review->id,
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product', 'images']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'content' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $review->update([
            'content' => $request->content,
            'rating' => $request->rating
        ]);

        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }

            // Store new images
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                ReviewImage::create([
                    'id_review' => $review->id,
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Delete associated images from storage
        foreach ($review->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        // Delete the review (this will cascade delete the images records)
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
