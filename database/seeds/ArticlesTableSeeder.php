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
        factory(\App\Article::class,50)->create()->each(function($art) use($users){
            $art->user()->associate($users->random());
            $art->save();
        });
    }
}
