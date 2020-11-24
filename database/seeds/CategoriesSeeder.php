<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [];
        $categories[] = ['title' => 'Home', 'category_status' => 'base', 'user_id' => 1, 'sort_order' => 0];

        foreach ($categories as $category) {
            \App\Category::insert([
                'title' => $category['title'],
                'category_status' => $category['category_status'],
                'user_id' => $category['user_id'],
                'sort_order' => $category['sort_order'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }
}
