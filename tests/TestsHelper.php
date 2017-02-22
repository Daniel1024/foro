<?php

namespace Tests;

use App\{
    Post, User
};

trait TestsHelper
{
    protected $defaultUser;

    public function defaultUser(array $atributes = [])
    {
        if ($this->defaultUser == null) {
            $this->defaultUser = factory(User::class)->create($atributes);
        }
        return $this->defaultUser;
    }

    public function createPost(array $atributes = [])
    {
        return factory(Post::class)->create($atributes);
    }
}