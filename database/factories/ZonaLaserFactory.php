<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ZonaLaser;
use Faker\Generator as Faker;

$factory->define(ZonaLaser::class, function (Faker $faker) {

	$completo=$faker->numberBetween(1000,800);
	$retoque=$faker->numberBetween(700,400);

	$time_completo=$faker->numberBetween(45,25).":00";
	$time_retoque=$faker->numberBetween(20,10).":00";
    return [
        'name'=>$faker->word,
        'completo'=>$completo,
        'retoque'=>$retoque,
        'time_completo'=>$time_completo,
        'time_retoque'=>$time_retoque
    ];
});
