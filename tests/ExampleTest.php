<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $name = 'Daniel Lopez';
        $email = 'admin@daniel.com';

        $user = factory(User::class)->create([
            'name' => $name,
            'email' => $email
        ]);

        $this->actingAs($user, 'api')
            ->visit('api/user')
            ->see($name)
            ->see($email);
    }
}
