<?php

use App\Models\Product;
use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
    	Categoria::create([
    		'name'=>'Por Sabor',
    		'description'=>'',
    		'sub_id'=>null,
    	]);
    	//2
    	Categoria::create([
    		'name'=>'Dulce',
    		'description'=>'',
    		'sub_id'=>1,
    	]);
    	//3
    	Categoria::create([
    		'name'=>'Salado',
    		'description'=>'',
    		'sub_id'=>1,
    	]);
    	//4
    	Categoria::create([
    		'name'=>'Por Marca',
    		'description'=>'',
    		'sub_id'=>null,
    	]);
    	//5
    	Categoria::create([
    		'name'=>'Varios',
    		'description'=>'',
    		'sub_id'=>null,
    	]);
/*    	Storage::deleteDirectory('public/products/');
    	Storage::makeDirectory('public/products/');*/

    	$faker = Faker\Factory::create();
    	factory(Product::class, 100)->create()->each(function ($product) use ($faker) {

    		$elem=$faker->randomElement([1,2,3,4,5]);
    		$product->categorias()->attach($elem);
	    });
    }
}
