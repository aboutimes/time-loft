<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'mobile' => '1'.$faker->unique()->isbn10,
        'description' => $faker->word,
        'last_login_ip' => $faker->ipv4,
        'password' =>  bcrypt('12345678'), // secret
        'remember_token' => str_random(10),
    ];
});
