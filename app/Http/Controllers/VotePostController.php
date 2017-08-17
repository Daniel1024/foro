<?php

namespace App\Http\Controllers;

use App\{
    Post, Vote
};

class VotePostController extends Controller
{
    public function upvote(Post $post)
    {
        // EL objeto post pasa por referencia
        // es decir que los cambios se veran reflejados en
        // $post->score
        Vote::upvote($post);

        return [
            'new_score' => $post->score,
        ];
    }

    public function downvote(Post $post)
    {
        // EL objeto post pasa por referencia
        // es decir que los cambios se veran reflejados en
        // $post->score
        Vote::downvote($post);

        return [
            'new_score' => $post->score,
        ];
    }

    public function undovote(Post $post)
    {
        Vote::undoVote($post);

        return [
            'new_score' => $post->score,
        ];
    }

}
