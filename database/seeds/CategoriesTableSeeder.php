<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
          '见闻',
          '技术',
          '作品',
          '影视',
          '音乐',
          '相册'
        ];
        foreach ($categories as $category){
            \App\Category::create([
                'category' => $category
            ]);
        }
    }
}
