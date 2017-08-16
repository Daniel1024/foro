<?php

namespace App\Http\Controllers;

use App\{
    Category, Post
};
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Category $category = null, Request $request)
    {
        $routeName = $request->route()->getName();

        list($orderColum, $orderDirection) = $this->getListOrder($request->get('orden'));

        $posts = Post::query()
            ->scopes($this->getListScopes($category, $routeName))
            ->orderBy($orderColum, $orderDirection)
            ->paginate();

        $posts->appends(request()->intersect(['orden']));

        $categoryItems = $this->getCategoryItems($routeName);

        //dd($posts->pluck('created_at')->toArray());

        return view('posts.index', compact('posts', 'category', 'categoryItems'));
    }

    public function show(Post $post, $slug)
    {
        if ($post->slug != $slug) {
            return redirect($post->url, 301);
        }
        return view('posts.show', compact('post'));
    }

    protected function getCategoryItems(String $routeName)
    {
        return Category::query()
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'title' => $category->name,
                    'full_url' => route('posts.index', $category)
                ];
            })->toArray();
    }

    protected function getListScopes(Category $category, String $routeName)
    {
        $scopes = [];

        if ($category->exists) {
            $scopes['category'] = [$category];
        }

        if ($routeName == 'posts.pending') {
            $scopes[] = 'pending';
        }

        if ($routeName == 'posts.completed') {
            $scopes[] = 'completed';
        }

        return $scopes;
    }

    protected function getListOrder($order)
    {
        if ($order == 'antiguos') {
            return ['created_at', 'asc'];
        }

        return ['created_at', 'desc'];
    }
}
