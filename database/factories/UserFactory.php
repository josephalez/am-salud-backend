<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

	$role=$faker->randomElement(["user","worker"]); // 'b'
	$gender=$faker->randomElement(["male","female"]);
    $area=null;
    if($role=="worker"){
        $area=$faker->numberBetween(1,2);
    }


    return [
    	'username'=>$faker->unique()->username,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'last_name'=>$faker->lastName,
        'email_verified_at' => now(),
        'password' => Hash::make('demo'),  
        'remember_token' => Str::random(10),
        'role'=>$role,
        'gender'=>$gender,
        'phone'=>$faker->e164PhoneNumber,
        'topic_firebase'=>$faker->unique()->regexify('[a-zA-Z0-9]{50}'),
        'area'=>$area
    ];
});
