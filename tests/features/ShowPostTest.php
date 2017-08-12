<?php

use App\{Post};

class ShowPostTest extends FeatureTestCase
{
    public function test_a_user_can_see_the_post_details()
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

        $this->visit($post->url)
            ->seeInElement('h1', $title)
            ->see($content)
            ->see($name);
    }

    public function test_old_urls_are_redirected()
    {
        $user = $this->defaultUser();

        $post = factory(Post::class)->make([
            'title' => 'Old Title',
        ]);

        $user->posts()->save($post);

        $url = $post->url;

        $post->update(['title' => 'New Title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }
}
