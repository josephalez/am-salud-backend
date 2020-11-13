<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            "id"=>1,
            "name"=>"Nutrición",
            "description"=>"Consulta con nuestro equipo profesional en nutrición y salud"
        ]);
        
        DB::table('services')->insert([
            "id"=>2,
            "name"=>"Láser Clinic",
            "description"=>"Depilación y tratamiento láser en zonas variadas del cuerpo"
        ]);
    }
}
