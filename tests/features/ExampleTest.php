<?php

use App\User;

class ExampleTest extends FeatureTestCase
{

    function test_basic_example()
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
