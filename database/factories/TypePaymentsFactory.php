<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TypePayments;
use Faker\Generator as Faker;



$factory->define(TypePayments::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'description'=>$faker->sentence(7)
    ];
});
