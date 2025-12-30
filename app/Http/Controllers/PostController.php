<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread)
    {


        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        $user = Auth::user();

        $board = $thread->board();

        if ($board->university_id !== null && $board->university_id !== $user->university_id) {
            abort(403, '他大学の掲示板に書き込めません。');
        }

        $post = new Post();
        $post->content = $validated['content'];
        $post->thread_id = $thread->id;
        $post->user_id = Auth::id();
        $post->ip_address = $request->ip();
    }
}
