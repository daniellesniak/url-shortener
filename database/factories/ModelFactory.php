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

$factory->define(App\Url::class, function (Faker\Generator $faker) {
   return [
       'slug' => str_random(6),
       'url' => $faker->url,
       'is_private' => $faker->randomElement([true, false])
   ];
});

$factory->define(App\UrlStat::class, function (Faker\Generator $faker) {
    $country_names = ['Germany', 'Italy', 'France', 'United Kingdom', 'Russia'];
    $country_codes = ['DE', 'IT', 'FR', 'GB', 'RU'];

    $rand_index = array_rand($country_names);
    return [
       'url_id' => function() {
            return factory('App\Url')->create()->id;
       },
       'platform' => $faker->randomElement(['Windows', 'Linux', 'iPhone', 'Macintosh']),
       'browser' => $faker->randomElement(['Internet Explorer', 'Opera', 'Safari', 'Google Chrome', 'Opera mini']),
       'ip' => $faker->ipv4,
       'country_name' => $country_names[$rand_index],
       'country_code' => $country_codes[$rand_index],
       'http_referer' => $faker->url,
       'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
       'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
   ];
});