<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Curriculum;
use App\Services\ActivityLogger;

class CommentController extends Controller
{
    public function store(Request $request, Curriculum $curriculum)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'curriculum_id' => $curriculum->id,
            'user_id'       => auth()->id(),
            'body'          => $request->body,
            'parent_id'     => $request->parent_id ?? null,
        ]);

        ActivityLogger::log(
            'Comment posted',
            auth()->user()->name . ' commented on ' . $curriculum->title,
            'fa-comment'
        );

        return back()->with('success', 'Comment posted.');
    }
}
