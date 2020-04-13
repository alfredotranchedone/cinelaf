<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\Cinelaf\Models\Regista::class, function (Faker $faker) {
    return [
        'nome' => $faker->firstName,
        'cognome' => $faker->lastName
    ];
});
