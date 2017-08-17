<?php

namespace App\Repositories;

use App\{
    Post, Vote
};
use Illuminate\Support\Facades\Auth;

class VoteRepository extends BaseRepository
{

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return new Vote();
    }

    public  function upvote(Post $post)
    {
        $this->addVote($post, 1);
    }

    public  function downvote(Post $post)
    {

        $this->addVote($post, -1);
    }

    protected  function addVote(Post $post, $amount)
    {
        $this->newQuery()
            ->updateOrCreate(
                ['post_id' => $post->id, 'user_id' => Auth::id()],
                ['vote' => $amount]
            );

        $this->refreshPostScore($post);
    }

    public  function undoVote(Post $post)
    {
        $this->newQuery()
            ->where([
                'post_id' => $post->id,
                'user_id' => Auth::id()
            ])->delete();

        $this->refreshPostScore($post);
    }

    protected  function refreshPostScore(Post $post)
    {
        $post->score = $this->newQuery()
            ->where(['post_id' => $post->id])
            ->sum('vote');

        $post->save();
    }
}