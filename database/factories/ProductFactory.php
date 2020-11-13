<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(Product::class, function (Faker $faker) {

	$files = Storage::files('/public/products/');



	//.$faker->image(storage_path('app/public/products/'),400,300, 'food', false)
	//$faker->image(storage_path('app/public/products/'),400,300, 'food', false);
	return [
        //
		"main_picture"=> $faker->randomElements($files)[0] ,
		"picture_uno"=> $faker->randomElements($files)[0],
		"picture_dos"=> $faker->randomElements($files)[0],
		"name"=>$faker->unique()->name,
		"description"=>$faker->sentence(5),
		"stock"=>100,
		"price"=>$faker->numberBetween(1000, 9000) 
	];
});
