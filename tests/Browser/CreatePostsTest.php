<?php

namespace Tests\Browser;

use App\Post;
use Tests\{DuskTestCase, TestsHelper};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class CreatePostsTest extends DuskTestCase
{
    use DatabaseMigrations, TestsHelper;

    protected $title = 'Esta es una pregunta';
    protected $content = 'Este es el contenido';

    function test_a_user_create_a_post()
    {
        $user = $this->defaultUser();

        //Having / Teniendo
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title', $this->title)
                ->type('content', $this->content)
                ->press('Publicar')
                // Test a user is redirected to the posts details after creating it
                ->assertPathIs('/posts/1-esta-es-una-pregunta');
        });

        //Then / Entonces
        $this->assertDatabaseHas('posts', [
            'title'     => $this->title,
            'slug'      => 'esta-es-una-pregunta',
            'content'   => $this->content,
            'pending'   => true,
            'user_id'   => $user->id,
        ]);

        $post = Post::query()->orderBy('id', 'DESC')->first();

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    function test_creating_a_post_requires_authentication()
    {
        $this->browse(function ($browser) {
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
                    'title'     => 'El campo título es obligatorio',
                    'content'   => 'El campo contenido es obligatorio',
                ]);
        });

    }
}
