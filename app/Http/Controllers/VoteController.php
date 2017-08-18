<?php

namespace App\Http\Controllers;

class VoteController extends Controller
{

    public function upvote($module, $votable)
    {
        // EL objeto post pasa por referencia
        // es decir que los cambios se veran reflejados en
        // $votable->score
        //Vote::upvote($votable);
        $votable->upVote();

        return [
            'new_score' => $votable->score,
        ];
    }

    public function downvote($module, $votable)
    {

        $votable->downVote();

        return [
            'new_score' => $votable->score,
        ];
    }

    public function undovote($module, $votable)
    {
        $votable->undoVote();

        return [
            'new_score' => $votable->score,
        ];
    }

}
