<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ServicesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TypePaymentsSeeder::class);
        //$this->call(ReservationsTableSeeder::class);
        
        $this->call(ZonaLaserSeeder::class);
        $this->call(NotasSeeder::class);
        $this->call(CreditoSeeder::class);
        $this->call(PaqueteSeeder::class);
        Artisan::call('passport:install');
    }
}
