<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::paginate(2);

        foreach ($reviews as $review) {
            $review->recent_comments = Comment::where('review_id', $review->id)
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
        }

        return view('review.index', compact('reviews'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function reviewCreate()
    {
        return view('review.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function newReview(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'movie' => 'required',
            'content' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'creator' => 'required',
        ]);    
        $review = new Review;
        $review->title = $request->input('title');
        $review->movie = $request->input('movie');
        $review->content = $request->input('content');
        $directory = public_path('reviewImages');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        $file = $request->file('image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $fileName);

        $review->image = 'reviewImages/' . $fileName;
        $review->user_id = $request->input('creator');
        
        $review->save();

        return redirect()->route('review.index')->with('success', 'review is aangemaakt');
    }

    /**
     * Display the specified resource.
     */
    public function reviewSingle(string $id)
    {
        $review = Review::where('id', $id)->first();
        $review->comments = Comment::where('review_id', $review->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('review.single', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function reviewEdit(string $id)
    {
        $review = Review::where('id', $id)->first();
        return view('review.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function reviewUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'movie' => 'required',
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $review = Review::where('id', $request->id)->first();
        $review->title = $request->input('title');
        $review->movie = $request->input('movie');
        $review->content = $request->input('content');
        $review->user_id = $request->input('creator');

        if ($request->hasFile('image')) {
            if ($review->image && file_exists(public_path($review->image))) {
                unlink(public_path($review->image));
            }
    
            $directory = public_path('reviewImages');
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $fileName);
    
            $review->image = 'reviewImages/' . $fileName;
        }

        $review->update();

        return redirect()->route('review.index')->with('success', 'Review has been updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroyReview(string $id)
    {
        
        $review = Review::find($id);
        $review->delete();

        return redirect()->route('review.index')->with('success', 'review is verwijderd');
    }
}
