<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\Cinelaf\Models\Film::class, function (Faker $faker) {
    return [
        'titolo' => $faker->sentence(rand(3,5)),
        'anno' => $faker->year,
        'locandina' => $faker->url,
        'user_id' => $faker->randomDigit,
        'valutazione' => $faker->numberBetween(1,5)
    ];
});
