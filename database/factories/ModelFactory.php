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

/**
 * Factory definition for model App\Models\User.
 */
$factory->define(App\Models\User::class, function ($faker) {
    return [
    	'active' => $faker->boolean,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => app('hash')->make('test'),

        //'address' => $faker->address
    ];
});
