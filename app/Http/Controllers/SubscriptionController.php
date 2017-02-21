<?php

namespace App\Http\Controllers;

use App\Post;
use Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Post $post)
    {
        Auth::user()->subscribeTo($post);

        return redirect($post->url);
    }

    public function unsubscribe(Post $post)
    {
        Auth::user()->unsubscribeFrom($post);

        return redirect($post->url);
    }
}
