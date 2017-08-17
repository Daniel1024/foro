<?php

namespace App\Http\Controllers;

use App\Post;

class VotePostController extends Controller
{

    public function upvote(Post $post)
    {
        // EL objeto post pasa por referencia
        // es decir que los cambios se veran reflejados en
        // $post->score
        //Vote::upvote($post);
        $post->upVote();

        return [
            'new_score' => $post->score,
        ];
    }

    public function downvote(Post $post)
    {

        $post->downVote();

        return [
            'new_score' => $post->score,
        ];
    }

    public function undovote(Post $post)
    {
        $post->undoVote();

        return [
            'new_score' => $post->score,
        ];
    }

}
