<?php

use Conekta\Conekta;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('demo', function () {
    Illuminate\Support\Facades\Redis::set('name', 'Taylor');
    $this->info('Display this on the screen');
})->describe('Display an inspiring quote');

Artisan::command('leer', function () {
    $name =Illuminate\Support\Facades\Redis::get('name');
    $this->info($name);
})->describe('Display an inspiring quote');

Artisan::command('prueba',function(){
	$this->info("hola");
	$this->info(Date('Y_m_d_H_i'));
});

Artisan::command('prueba',function(){
	$this->info(config('services.conekta.laserKey'));
});