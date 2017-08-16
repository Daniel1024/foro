<?php

namespace Tests\Browser;

use App\{
    Category, Post
};
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreatePostsTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_create_a_post()
    {
        // Having - Teniendo
        $title = 'Esto es una pregunta';
        $content = 'Este es el contenido';
        $user = $this->defaultUser();
        $category = factory(Category::class)->create();

        $this->browse(function (Browser $browser) use ($title, $content, $user, $category){

            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title', $title)
                ->type('content', $content)
                ->select('category_id', $category->id)
                ->press('Publicar')
                ->assertPathIs('/posts/1-esto-es-una-pregunta');
        });

        // Then - Entonces
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'content' => $content,
            'pending' => true,
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $post = Post::query()->first();

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

    }

    function test_creating_a_post_requires_authenication()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('posts.create')
                ->assertRouteIs('token');
        });
    }

    function test_create_post_form_validation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->defaultUser())
                ->visitRoute('posts.create')
                ->press('Publicar')
                ->assertRouteIs('posts.create')
                ->assertSeeErrors([
                    'title' => 'El campo título es obligatorio',
                    'content' => 'El campo contenido es obligatorio',
                ]);
        });

    }
}
