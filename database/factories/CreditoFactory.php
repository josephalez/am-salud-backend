<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Credito;
use Faker\Generator as Faker;

$factory->define(Credito::class, function (Faker $faker) {
    return [
        //
        'name'=>$faker->unique()->word,
        'description'=>$faker->text(200)
    ];
});
