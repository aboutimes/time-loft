<?php

use Faker\Generator as Faker;

$factory->define(App\Footprint::class, function (Faker $faker) {
    return [
        'desc' => $faker->word,
        'lng' => $faker->randomFloat(6, -180, 180),
        'lat' => $faker->randomFloat(6, -90, 90)
    ];
});
