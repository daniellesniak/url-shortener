<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\UrlStat::class, function (Faker\Generator $faker) {
   return [
       'url_id' => $faker->randomElement([12, 11, 10, 9, 8, 7]),
       'platform' => $faker->randomElement(['Windows', 'Macintosh', 'Linux', 'iPhone']),
       'browser' => $faker->randomElement(['Chrome', 'Mozilla Firefox', 'Internet Explorer']),
       'ip' => $faker->ipv4,
       'country_name' => $faker->country,
       'country_code' => $faker->countryCode,
       'http_referer' => $faker->url,
       'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
       'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
   ];
});