<?php

namespace Tests\Integration;

use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{

    use DatabaseTransactions;

    public function test_a_slug_is_generated_and_saved_to_the_database()
    {
        $post = $this->createPost([
            'title' => 'Como instalar Laravel',
        ]);

        /*$this->seeInDatabase('posts', [
            'slug' => 'como-instalar-laravel',
        ]);*/

        $this->assertSame(
            'como-instalar-laravel',
            $post->fresh()->slug
        );

    }


    function test_the_url_of_the_post_is_generated()
     {
         $user = $this->defaultUser();
         $post = factory(Post::class)->make();
         $user->posts()->save($post);

         $this->assertSame(
             $post->url,
             route('posts.show', [$post->id, $post->slug])
         );
     }
}
