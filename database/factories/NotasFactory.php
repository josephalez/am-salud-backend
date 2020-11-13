<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Nota;
use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Nota::class, function (Faker $faker) {
	$client=Client::all()->random(1)[0]->id;
	$worker=User::where('role','<>','user')->get()->random(1)[0]->id;
    return [
        //
        'client'=>$client,
        'worker'=>$worker,
        'nota'=>$faker->text
    ];
});
