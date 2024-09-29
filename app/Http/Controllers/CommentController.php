<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function newComment(Request $request)
    {
        $validatedData = $request->validate([
            'comment' => 'required|string|max:500',
            'review' => 'required',
            'creator' => 'required',
        ]);


        $comment = new Comment;
        $comment->review_id = $request->input('review');
        $comment->reaction = $request->input('comment');
        $comment->user_id = $request->input('creator');
        $user = User::where('id', $comment->user_id)->first();
        $comment->name = $user->name;
        $comment->email = $user->email;
        
        $comment->save();

        return redirect()->back()->with('success', 'comment is aangemaakt');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $request->validate([
            'comment' => 'required|max:500',
        ]);

        $comment->reaction = $request->input('comment');
        $comment->save();

        return back()->with('success', 'Comment updated successfully.');

    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

}
