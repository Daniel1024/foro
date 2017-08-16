<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\{
    Category, Post
};

class CreatePostsTest extends FeatureTestCase
{

    function test_a_user_create_a_post()
    {
        // Having - Teniendo
        $title = 'Esto es una pregunta';
        $content = 'Este es el contenido';

        $this->actingAs($user = $this->defaultUser());

        $category = factory(Category::class)->create();

        // When - Cuando
        $this->visitRoute('posts.create')
            ->type($title, 'title')
            ->type($content, 'content')
            ->select($category->id, 'category_id')
            ->press('Publicar');

        // Then - Entonces
        $this->seeInDatabase('posts', [
            'title' => $title,
            'content' => $content,
            'pending' => true,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $post = Post::query()->first();

        $this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->seePageIs($post->url);
    }

    function test_creating_a_post_requires_authenication()
    {
        // Having - Teniendo
        $this->visitRoute('posts.create')
            ->seeRouteIs('token');
    }

    function test_create_post_form_validation()
    {
        $this->actingAs($this->defaultUser())
            ->visit(route('posts.create'))
            ->press('Publicar')
            ->seePageIs(route('posts.create'))
            ->seeErrors([
                'title' => 'El campo título es obligatorio',
                'content' => 'El campo contenido es obligatorio',
            ]);
            /*->seeInElement('#field_title .help-block', 'El campo título es obligatorio')
            ->seeInElement('#field_content .help-block', 'El campo contenido es obligatorio');*/
    }

}
