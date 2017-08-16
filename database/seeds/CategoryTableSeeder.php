<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Laravel',
            'PHP',
            'Javascript',
            'Vue.js',
            'CSS-3',
            'Sass',
            'Less',
            'Git',
            'IDE',
            'Otras tecnologías'
        ];

        foreach ($categories as $category) {
            Category::query()
                ->create(['name' => $category]);
        }
    }
}
