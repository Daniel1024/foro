<?php

use App\Post;

class ShowPostTest extends FeatureTestCase
{
    function test_a_user_can_see_the_post_details()
    {
        //having
        $user = $this->defaultUser([
            'name' => 'Daniel Lopez'
        ]);

        $post = factory(Post::class)->make([
            'title'     => 'Como instalar Laravel',
            'content'   => 'Este es el contenido del post'
        ]);

        $user->posts()->save($post);

        //when
        $this->visit(route('posts.show', $post))
            ->seeInElement('h1', $post->title)
            ->see($post->content)
            ->see($user->name);
    }
}
