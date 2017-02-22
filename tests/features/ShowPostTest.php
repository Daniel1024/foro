<?php

class ShowPostTest extends FeatureTestCase
{
    function test_a_user_can_see_the_post_details()
    {
        //having
        $post = $this->createPost([
            'title'     => 'Como instalar Laravel',
            'content'   => 'Este es el contenido del post',
            'user_id'   => $this->defaultUser(['first_name' => 'Daniel', 'last_name' => 'López'])->id,
        ]);

        //when
        $this->visit($post->url)
            ->seeInElement('h1', $post->title)
            ->see($post->content)
            ->see('Daniel López');
    }

    function test_old_urls_are_redirected()
    {
        $post = $this->createPost([
            'title' => 'Old title',
        ]);

        $url = $post->url;

        $post->update(['title' => 'New title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }

}
