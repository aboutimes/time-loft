<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    $images = [
        '/images/bg.jpg',
        '/images/bg2.jpg',
        '/images/bg3.jpg',
        '/images/bg4.jpg',
    ];
    return [
        'title' => $faker->word,
        'author' => $faker->name,
        'write_at' => $faker->unixTime($max = 'now'),
        'background_url' => $images[$faker->numberBetween(0,3)],
        'content' => $faker->paragraph(),
    ];
});
