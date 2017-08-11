<?php

use App\{Post};

class ShowPostTest extends FeatureTestCase
{
    function test_a_user_can_see_the_post_details()
    {
        $name = 'Daniel López';
        $title = 'Como instalar Laravel';
        $content = 'Este es el contenido del post';

        $user = $this->defaultUser([
            'name' => $name,
        ]);

        $post = factory(Post::class)->make([
            'title' => $title,
            'content' => $content
        ]);

        $user->posts()->save($post);

        $this->visit(route('posts.show', $post))
            ->seeInElement('h1', $title)
            ->see($content)
            ->see($name);
    }
}
