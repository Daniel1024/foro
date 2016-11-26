<?php

class ExampleTest extends FeatureTestCase
{

    function test_basic_example()
    {
        $user = factory(\App\User::class)->create([
            'name'  => 'Daniel Lopez',
            'email' => 'daniel@admin.com'
        ]);

        $this->actingAs($user, 'api')
            ->visit('api/user')
            ->see('Daniel Lopez')
            ->see('daniel@admin.com');
    }
}
