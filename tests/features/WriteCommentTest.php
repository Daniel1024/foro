<?php

use Illuminate\Support\Facades\Notification;

class WriteCommentTest extends FeatureTestCase
{

    public function test_a_user_can_writer_a_comment()
    {
        Notification::fake();

        $post = $this->createPost();

        $this->actingAs($user = $this->defaultUser())
            ->visit($post->url)
            ->type('Un comentario', 'comment')
            ->press('Publicar comentario');

        $this->seeInDatabase('comments', [
            'comment' => 'Un comentario',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->seePageIs($post->url);
    }
}
