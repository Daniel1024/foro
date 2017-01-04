<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /*Creando un comentario*/

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->paginate();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post, $slug) {
        //abort_unless($post->slug == $slug, 404);

        if ($post->slug != $slug) {
            return redirect($post->url, 301);
        }

        return view('posts.show', compact('post'));
    }
}
