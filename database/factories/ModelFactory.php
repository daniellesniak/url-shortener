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
	$platforms_arr = [
        	'Windows',
        	'Linux',
        	'Macintosh',
        	'Chrome OS',
        	'Android',
        	'iPhone',
        	'PlayStation 4',
        	'XBOX One'
        ];
        $browsers_arr = [
        	'Chrome',
        	'Firefox',
        	'Safari',
        	'Opera',
        	'IEMobile',
        	'Lynx',
        	'Curl',
        	'Wget'
        ];

	return [
        DB::table('urls')->insert([
        	'url' => $faker->company.'.com',
        	'string_id' => str_random(6),
        	'created_at' => null,
        	'updated_at' => null
        ]),
        DB::table('url_stats')->insert([
        	'url_id' => 1,
        	'platform' => $platforms_arr[array_rand($platforms_arr)],
        	'browser' => $browsers_arr[array_rand($browsers_arr)],
        	'ip' => $faker->ipv4,
        	'country_name' => $faker->country,
        	'country_code' => $faker->stateAbbr,
        	'created_at' => $faker->dateTime(),
        	'updated_at' => $faker->dateTime()
        ])
	];
});