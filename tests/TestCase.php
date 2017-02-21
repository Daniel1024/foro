<?php

use App\Post;
use App\User;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://foro.app';

    protected $defaultUser;


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


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
