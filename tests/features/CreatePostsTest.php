<?php

use App\{
    Category, Post
};

class CreatePostsTest extends FeatureTestCase
{
    function test_a_user_create_a_post()
    {
        //Having / Teniendo
        $title = 'Esta es una pregunta';
        $content = 'Este es el contenido';

        $this->actingAs($user = $this->defaultUser());

        $category = factory(Category::class)->create();

        //When / Cuando
        $this->visitRoute('posts.create')
            ->type($title, 'title')
            ->type($content, 'content')
            ->select($category->id, 'category_id')
            ->press('Publicar');

        //Then / Entonces
        $this->seeInDatabase('posts', [
            'title'         => $title,
            'slug'          => 'esta-es-una-pregunta',
            'content'       => $content,
            'pending'       => true,
            'user_id'       => $user->id,
            'category_id'   => $category->id,
        ]);

        $post = Post::query()->orderBy('id', 'DESC')->first();

        $this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Test a user is redirected to the posts details after creating it
        $this->seePageIs($post->url);
    }

    function test_creating_a_post_requires_authentication()
    {
        $this->visitRoute('posts.create')
            ->seeRouteIs('token');
    }

    function test_create_post_form_validation()
    {
        $this->actingAs($this->defaultUser())
            ->visitRoute('posts.create')
            ->press('Publicar')
            ->seeRouteIs('posts.create')
            ->seeErrors([
                'title'     => 'El campo título es obligatorio',
                'content'   => 'El campo contenido es obligatorio',
            ]);
    }

}
