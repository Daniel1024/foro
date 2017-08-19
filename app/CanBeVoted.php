<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Collective\Html\HtmlFacade as Html;

trait CanBeVoted
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function userVote()
    {
        return $this->morphOne(Vote::class, 'votable')
            ->where('user_id', Auth::id())
            ->withDefault();
    }

    public function getCurrentVoteAttribute()
    {
        if (Auth::check()) {
            return $this->userVote->vote;
        }
        return null;
    }

    public function getVoteComponentAttribute()
    {
        if (Auth::check()) {
            return Html::tag('app-vote', '', [
                'module' => $this->getTable(),
                'id' => $this->id,
                'score' => $this->score,
                'vote' => $this->current_vote,
            ]);
        }

        return '';
    }

    public function getVoteFrom(User $user)
    {
        return $this->votes()
            ->where('user_id', $user->id)
            ->value('vote');
    }

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
        $this->votes()
            ->updateOrCreate(
                ['user_id' => Auth::id()],
                ['vote' => $amount]
            );

        $this->refreshPostScore();
    }

    public function undoVote()
    {
        $this->votes()
            ->where('user_id', Auth::id())
            ->delete();
        /*Vote::query()
            ->where([
                'post_id' => $this->id,
                'user_id' => Auth::id()]
            )->delete();*/

        $this->refreshPostScore();
    }

    protected function refreshPostScore()
    {
        $this->score = $this->votes()
            ->sum('vote');

        $this->save();
    }

}
