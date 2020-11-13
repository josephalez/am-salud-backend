<?php

use App\Models\Paquete;
use Illuminate\Database\Seeder;

class PaqueteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        //paquete am cash
    	Paquete::create([
    		'credit_id'=>1,
    		'nombre'=>'AM CASH paquete 5%',
    		'points'=>5000,
    		'bonus'=>5,
    		'status'=>1
    	]);
    	Paquete::create([
    		'credit_id'=>1,
    		'nombre'=>'AM CASH paquete 10%',
    		'points'=>10000,
    		'bonus'=>10,
    		'status'=>1
    	]);
    	Paquete::create([
    		'credit_id'=>1,
    		'nombre'=>'AM CASH paquete 15%',
    		'points'=>15000,
    		'bonus'=>15,
    		'status'=>1
    	]);
    	//Paquete lc cash
    	Paquete::create([
    		'credit_id'=>2,
    		'nombre'=>'LC CASH paquete 5%',
    		'points'=>5000,
    		'bonus'=>5,
    		'status'=>1
    	]);
    	Paquete::create([
    		'credit_id'=>2,
    		'nombre'=>'LC CASH paquete 10%',
    		'points'=>10000,
    		'bonus'=>10,
    		'status'=>1
    	]);
    	Paquete::create([
    		'credit_id'=>2,
    		'nombre'=>'LC CASH paquete 15%',
    		'points'=>15000,
    		'bonus'=>15,
    		'status'=>1
    	]);
    }
}
