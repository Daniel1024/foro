<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WriteCommentTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_a_user_can_write_a_comment()
    {
        Notification::fake();

        $post = $this->createPost();

        $user = $this->defaultUser();

        $this->browse(function(Browser $browser) use($post, $user){
            $browser->loginAs($user)
                ->visit($post->url)
                ->type('comment', 'Un comentario')
                ->press('Publicar comentario')
                ->assertPathIs("/posts/1-{$post->slug}");
        });

        $this->assertDatabaseHas('comments', [
            'comment' => 'Un comentario',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

    }
}
