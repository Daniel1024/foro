<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Post;

class PostModelTest extends TestCase
{
    //use \Illuminate\Foundation\Testing\DatabaseTransactions;

    public function test_adding_a_title_generates_a_slug()
    {
        $post = new Post([
            'title' => 'Como instalar Laravel',
        ]);

        $this->assertSame('como-instalar-laravel', $post->slug);

    }

    public function test_edditing_the_title_changes_the_slug()
    {
        $post = new Post([
            'title' => 'Como instalar Laravel',
        ]);

        $post->title = 'Como instalar Laravel 5.1 LTS';

        $this->assertSame('como-instalar-laravel-51-lts', $post->slug);
    }
}
