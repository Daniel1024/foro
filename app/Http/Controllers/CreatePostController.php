<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatePostController extends Controller
{
    public function create()
    {
        $categories = Category::query()
            ->pluck('name', 'id')
            ->toArray();

        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Auth::user()->createPost($request->all());

        return redirect($post->url);
    }
}
