<?php

use App\{
    Category, Post, User
};
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(\App\Post::class, 100)->create();

        $users = User::query()
            ->select('id')
            ->get();

        $categories = Category::query()
            ->select('id')
            ->get();

        for ($i = 0; $i < 100; ++$i) {
            factory(Post::class)->create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
