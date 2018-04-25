<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'author' => $faker->name,
        'content' => $faker->paragraph(),
    ];
});
