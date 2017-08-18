<?php

namespace Tests\Feature;

use App\Post;
use Tests\FeatureTestCase;

class VoteForPostTest extends FeatureTestCase
{
    public function test_a_user_can_upvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson("/posts/{$post->id}/upvote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => 1]);

        $this->seeInDatabase('votes', [
            'votable_id' => $post->id,
            'votable_type' => Post::class,
            'user_id' => $user->id,
            'vote' => 1,
        ]);

        $this->assertSame(1, $post->fresh()->score);
    }

    public function test_a_user_can_downvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $this->postJson("/posts/{$post->id}/downvote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => -1]);

        $this->seeInDatabase('votes', [
            'votable_id' => $post->id,
            'votable_type' => Post::class,
            'user_id' => $user->id,
            'vote' => -1,
        ]);

        $this->assertSame(-1, $post->fresh()->score);
    }

    public function test_a_user_can_unvote_for_a_post()
    {
        $this->actingAs($user = $this->defaultUser());

        $post = $this->createPost();

        $post->upvote();

        $this->deleteJson("/posts/{$post->id}/vote")
            ->seeStatusCode(200)
            ->seeJson(['new_score' => 0]);

        $this->dontSeeInDatabase('votes', [
            'votable_id' => $post->id,
            'votable_type' => Post::class,
            'user_id' => $user->id,
        ]);

        $this->seeInDatabase('posts', [
            'id' => $post->id,
            'score' => 0
        ]);
    }

    public function test_a_guest_user_cannot_vote_for_a_post()
    {
        $user = $this->defaultUser();

        $post = $this->createPost();

        $this->postJson("/posts/{$post->id}/downvote")
            ->seeStatusCode(401)
            ->seeJson(['error' => 'Unauthenticated.']);

        $this->dontSeeInDatabase('votes', [
            'votable_id' => $post->id,
            'votable_type' => Post::class,
            'user_id' => $user->id,
        ]);

        $this->seeInDatabase('posts', [
            'id' => $post->id,
            'score' => 0
        ]);

    }

}
