<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'Daniel',
            'last_name' => 'López',
            'username' => 'daniel1024',
            'email' => 'admin@daniel.net',
            'role' => 'admin'
        ]);
    }
}
