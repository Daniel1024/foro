<?php


class CreatePostsTest extends FeatureTestCase
{

    function test_a_user_create_a_post()
    {
        // Having - Teniendo
        $title = 'Esto es una pregunta';
        $content = 'Este es el contenido';

        $this->actingAs($user = $this->defaultUser());

        // When - Cuando
        $this->visit(route('posts.create'))
            ->type($title, 'title')
            ->type($content, 'content')
            ->press('Publicar');

        // Then - Entonces
        $this->seeInDatabase('posts', [
            'title' => $title,
            'content' => $content,
            'pending' => true,
            'user_id' => $user->id,
        ]);

        $this->see($title);
    }

    function test_creating_a_post_requires_authenication()
    {
        // Having - Teniendo
        $this->visit(route('posts.create'))
            ->seePageIs(route('login'));
    }

}
