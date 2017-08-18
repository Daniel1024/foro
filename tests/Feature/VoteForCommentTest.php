<?php

namespace Tests\Feature;

use App\Comment;
use Tests\FeatureTestCase;

class VoteForCommentTest extends FeatureTestCase
{
    protected $comment;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->actingAs($this->user = $this->defaultUser());

        $this->comment = factory(Comment::class)->create();
    }

    public function test_a_user_can_upvote_for_a_comment()
    {
        $this->postJson("/comments/{$this->comment->id}/upvote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => '1']);

        $this->comment->refresh();

        $this->assertSame(1, $this->comment->current_vote);
        $this->assertSame(1, $this->comment->score);
    }

    public function test_a_user_can_downvote_for_a_comment()
    {
        $this->postJson("/comments/{$this->comment->id}/downvote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => '-1']);

        $this->comment->refresh();

        $this->assertSame(-1, $this->comment->current_vote);
        $this->assertSame(-1, $this->comment->score);
    }

    public function test_a_user_can_unvote_a_comment()
    {
        $this->comment->upvote();

        $this->deleteJson("/comments/{$this->comment->id}/vote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => 0]);

        $this->comment->refresh();

        $this->assertNull($this->comment->current_vote);
        $this->assertSame(0, $this->comment->score);
    }

    public function test_a_guest_user_cannot_vote_for_a_comment()
    {
        \Auth::logout();

        $this->postJson("/comments/{$this->comment->id}/upvote")
            ->seeStatusCode(401)
            ->seeJson(['error' => 'Unauthenticated.']);

        $this->comment->refresh();

        $this->assertNull($this->comment->current_vote);
        $this->assertSame(0, $this->comment->score);
    }

}
