<?php

use Illuminate\Database\Seeder;

class FootprintsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::pluck('id');
        $articles = \App\Article::pluck('id');
        factory(\App\Footprint::class,50)->create()->each(function($fpt) use($users, $articles){
            $fpt->user()->associate($users->random());
            $fpt->article()->associate($articles->random());
            $fpt->save();
        });
    }
}
