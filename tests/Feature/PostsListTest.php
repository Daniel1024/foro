<?php

namespace Tests\Feature;

use App\Category;
use Tests\FeatureTestCase;
use App\Post;
use Carbon\Carbon;

class PostsListTest extends FeatureTestCase
{

    public function test_a_user_can_see_the_posts_list_and_go_the_details()
    {
        $post = $this->createPost([
            'title' => '¿Debo utilizar Laravel 5.3 o 5.1 LTS?'
        ]);

        $this->visit('/')
            ->seeInElement('h1', 'Posts')
            ->see('¿Debo utilizar Laravel 5.3 o 5.1 LTS?')
            ->click($post->title)
            ->seePageIs($post->url);
    }

    public function test_a_user_can_see_posts_by_category()
    {
        $laravel = factory(Category::class)->create([
            'name' => 'Laravel',
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js',
        ]);

        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id,
        ]);

        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id,
        ]);


        $this->visitRoute('posts.index')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            ->within('.categories', function () {
                $this->click('Laravel');
            })
            ->seeInElement('h1', 'Posts de Laravel')
            ->see($laravelPost->title)
            ->dontSee($vuePost->title);
    }

    public function test_a_user_can_see_posts_filtered_by_status()
    {
        $pendingPost = factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true
        ]);

        $completePost = factory(Post::class)->create([
            'title' => 'Post completado',
            'pending' => false
        ]);

        $this->visitRoute('posts.pending')
            ->see($pendingPost->title)
            ->dontSee($completePost->title);

        $this->visitRoute('posts.completed')
            ->see($completePost->title)
            ->dontSee($pendingPost->title);

    }

    public function test_the_posts_are_paginated()
    {
        $first = factory(Post::class)->create([
            'title' => 'Post más antiguo',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        factory(Post::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay(),
        ]);

        $last = factory(Post::class)->create([
            'title' => 'Post más reciente',
        ]);

        $this->visit('/')
            ->see($last->title)
            ->dontSee($first->title)
            ->click('2')
            ->see($first->title)
            ->dontSee($last->title);
    }
}
