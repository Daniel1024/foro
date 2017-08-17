<?php

namespace App;

use Illuminate\Support\Facades\Auth;

trait CanBeVoted
{

    // Votes:

    public function upvote()
    {
        $this->addVote(1);
    }

    public function downvote()
    {

        $this->addVote(-1);
    }

    protected function addVote($amount)
    {
        Vote::query()
            ->updateOrCreate(
                ['post_id' => $this->id, 'user_id' => Auth::id()],
                ['vote' => $amount]
            );

        $this->refreshPostScore();
    }

    public function undoVote()
    {
        Vote::query()
            ->where([
                    'post_id' => $this->id,
                    'user_id' => Auth::id()]
            )->delete();

        $this->refreshPostScore();
    }

    protected function refreshPostScore()
    {
        $this->score = Vote::query()
            ->where(['post_id' => $this->id])
            ->sum('vote');

        $this->save();
    }

}
