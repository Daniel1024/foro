<?php

namespace App\Http\Controllers;

use App\Post;
use Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Post $post)
    {
        /*Subscription::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);*/

        Auth::user()->subscribeTo($post);

        return redirect($post->url);
    }

    public function unSubscribe(Post $post)
    {
        /*Subscription::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);*/

        Auth::user()->unSubscribeFrom($post);

        return redirect($post->url);
    }
}
