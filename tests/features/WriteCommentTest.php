<?php

class WriteCommentTest extends FeatureTestCase
{

    function test_a_user_can_write_a_comment()
    {
        $comment = 'Un comentario';
        $post = $this->createPost();
        $user = $this->defaultUser();

        $this->actingAs($user)
            ->visit($post->url)
            ->type($comment, 'comment')
            ->press('Publicar comentario');

        $this->seeInDatabase('comments', [
            'comment'   => $comment,
            'user_id'   => $user->id,
            'post_id'   => $post->id,
        ]);

        $this->seePageIs($post->url);
    }
}
