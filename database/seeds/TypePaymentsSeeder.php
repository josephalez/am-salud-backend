<?php

use App\User;
use App\Models\TypePayments;
use Illuminate\Database\Seeder;

class TypePaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        factory(TypePayments::class)->create(["name"=>"efectivo"]);
        factory(TypePayments::class)->create(["name"=>"saldo virtual"]);
        factory(TypePayments::class)->create(["name"=>"conkecta"]); 
        factory(TypePayments::class)->create(["name"=>"paypal"]);


    }
}
