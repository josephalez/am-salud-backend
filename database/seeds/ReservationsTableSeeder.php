<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->total(2,1,2,1,5221);
        $this->total(2,1,3,2,1232);

        $this->total(3,1,2,1,1321);
        $this->total(3,1,3,2,5332);

        $this->total(4,1,2,1,2421);
        $this->total(4,1,3,2,1432);

        $this->total(5,1,2,1,1521);
        $this->total(5,1,3,2,3532);

        $this->total(6,1,2,1,621);
        $this->total(6,1,3,2,1632);
    }


    public function total($mes,$user,$worker,$service,$monto){
        for ($i=0; $i < 10; $i++) { 

            DB::table('reservations')->insert([
                "user"=>$user,
                "worker"=>$worker,
                "monto"=>$monto,
                "reservation_start"=>"2020-".$mes."-".(12+$i)." ".(12+$i).":10:00",
                "reservation_end"=>"2020-".$mes."-".(12+$i)." ".(12+$i).":45:00",
                "canceled"=>(($i+1)%2)&&($i>5)?1:0,
                "confirmed"=>(($i+1)%2)&&($i>5)?0:1,
                "servicio_id"=>$service,
                "payment_id"=>1
            ]);

        }
    }
}
