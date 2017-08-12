<?php

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

        $post = $this->createPost([
            'title' => $title,
            'content' => $content,
            'user_id' => $user->id,
        ]);

        $this->visit($post->url)
            ->seeInElement('h1', $title)
            ->see($content)
            ->see($name);
    }

    public function test_old_urls_are_redirected()
    {
        $post = $this->createPost([
            'title' => 'Old Title',
        ]);

        $url = $post->url;

        $post->update(['title' => 'New Title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }
}
