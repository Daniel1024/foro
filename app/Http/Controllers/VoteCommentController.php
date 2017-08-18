<?php

namespace App\Http\Controllers;

use App\Comment;

class VoteCommentController extends Controller
{

    public function upvote(Comment $comment)
    {
        $comment->upVote();

        return ['new_score' => $comment->score];
    }

    public function downvote(Comment $comment)
    {

        $comment->downVote();

        return ['new_score' => $comment->score];
    }

    public function undovote(Comment $comment)
    {
        $comment->undoVote();

        return ['new_score' => $comment->score];
    }

}
