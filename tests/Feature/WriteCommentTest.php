<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
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

    function test_create_comment_form_validation()
    {
       $post = $this->createPost();

       $user = $this->defaultUser();

       $this->actingAs($user)
           ->visit($post->url)
           ->press('Publicar comentario')
           ->seePageIs($post->url)
           ->seeErrors([
                   'comment' => 'El campo comentario es obligatorio',
               ]);
    }
}
