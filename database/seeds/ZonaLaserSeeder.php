<?php

use App\Models\ZonaLaser;
use Illuminate\Database\Seeder;

class ZonaLaserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(ZonaLaser::class,40)->create();
    }
}
