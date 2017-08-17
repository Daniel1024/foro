<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\{Post, User, Vote};
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APostCanBeVotedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Post
     */
    protected $post;

    function setUp()
    {
        parent::setUp();

        $this->user = $this->defaultUser();

        $this->post = $this->createPost();

        $this->actingAs($this->user);
    }

    public function test_a_post_can_be_upvoted()
    {
        $this->post->upvote();

        $this->seeInDatabase('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $this->post->score);
    }

    public function test_a_post_can_be_downvoted()
    {
        $this->post->downvote();

        $this->seeInDatabase('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote' => -1,
        ]);

        $this->assertSame(-1, $this->post->score);
    }

    public function test_a_post_cannot_be_upvoted_twice_by_the_same_user()
    {
        $this->post->upvote();

        $this->post->upvote();

        $this->assertSame(1, $this->post->query()->count());

        $this->assertSame(1, $this->post->score);
    }

    public function test_a_post_cannot_be_downvoted_twice_by_the_same_user()
    {
        $this->post->downvote();

        $this->post->downvote();

        $this->assertSame(1, $this->post->query()->count());

        $this->assertSame(-1, $this->post->score);
    }

    public function test_a_user_can_switch_from_upvote_to_downvote()
    {
        $this->post->upvote();

        $this->post->downvote();

        $this->assertSame(1, $this->post->query()->count());

        $this->assertSame(-1, $this->post->score);
    }

    public function test_a_user_can_switch_from_downvote_to_upvote()
    {
        $this->post->downvote();

        $this->post->upvote();

        $this->assertSame(1, $this->post->query()->count());

        $this->assertSame(1, $this->post->score);
    }

    public function test_the_post_score_is_calculated_correctly()
    {
        Vote::query()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->anyone()->id,
            'vote' => 1,
        ]);

        $this->post->upvote();

        $this->assertSame(2, Vote::query()->count());

        $this->assertSame(2, $this->post->score);
    }

    public function test_a_post_can_be_unvoted()
    {
        $this->post->upvote();

        $this->post->undoVote();

        $this->dontSeeInDatabase('votes', [
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'vote' => 1,
        ]);

        $this->assertSame(0, $this->post->score);
    }

}
