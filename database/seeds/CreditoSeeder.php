<?php

use App\Models\Credito;
use Illuminate\Database\Seeder;

class CreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        factory(Credito::class)->create(['name'=>'AM CASH']);
        factory(Credito::class)->create(['name'=>'LC CASH']);
        //factory(Credito::class)->create(['name'=>'saldo demo']);
        
    }
}
