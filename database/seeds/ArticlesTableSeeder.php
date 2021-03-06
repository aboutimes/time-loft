<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::pluck('id');
        $categories = \App\Category::pluck('id');
        $tags = \App\Tag::pluck('id');
        factory(\App\Article::class,50)->create()->each(function($art) use($users, $categories, $tags){
            $art->user()->associate($users->random());
            $art->category()->associate($categories->random());
            $art->tag()->associate($tags->random());
            $art->save();
        });
    }
}
