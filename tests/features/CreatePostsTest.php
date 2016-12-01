<?php

class CreatePostsTest extends FeatureTestCase
{
    function test_a_user_create_a_post()
    {
        //Having / Teniendo
        $title = 'Esta es una pregunta';
        $content = 'Este es el contenido';

        $this->actingAs($user = $this->defaultUser());

        //When / Cuando
        $this->visit(route('posts.create'))
            ->type($title, 'title')
            ->type($content, 'content')
            ->press('Publicar');

        //Then / Entonces
        $this->seeInDatabase('posts', [
            'title'     => $title,
            'slug'      => 'esta-es-una-pregunta',
            'content'   => $content,
            'pending'   => true,
            'user_id'   => $user->id,
        ]);

        // Test a user is redirected to the posts details after creating it
        $this->see($title);
    }

    function test_creating_a_post_requires_authentication()
    {
        $this->visit(route('posts.create'))
            ->seePageIs(route('login'));
    }

    function test_create_post_form_validation()
    {
        $this->actingAs($this->defaultUser())
            ->visit(route('posts.create'))
            ->press('Publicar')
            ->seePageIs(route('posts.create'))
            ->seeErrors([
                'title'     => 'El campo título es obligatorio',
                'content'   => 'El campo contenido es obligatorio',
            ]);
    }

}
