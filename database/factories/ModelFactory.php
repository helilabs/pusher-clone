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

$factory->define(\App\Models\Token::class, function (Faker\Generator $faker) {
    return [
        'app_id' => $faker->uuid,
        'secret' => str_random(32),
    ];
});
