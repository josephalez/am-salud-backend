<?php

use App\Hour;
use App\User;
use Stripe\Token;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Client;
use App\Models\StripeCard;
use App\Models\StripeClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

  public function run(){

    $files = Storage::files('/public/nostrospic/');

    factory(User::class,1)->create([
      'username'=>'admin',
      "role"=>"admin",
      "name"=>"prueba",
      "last_name"=>"prueba",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'admin@amsalud.com',
      'password'  =>  Hash::make('demo'),
    ]);

    factory(User::class,1)->create([
      'username'=>'atencion',
      "role"=>"atencion",
      "name"=>"prueba atencion",
      "last_name"=>"prueba atencion",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'atencion@amsalud.com',
      'password'  =>  Hash::make('demo'),
    ]);

    factory(User::class,1)->create([
      'username'=>'terapeutalaser',
      "role"=>"worker",
      'area'=>2,
      "name"=>"terapuetalaser",
      "last_name"=>"prueba",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'terapeutalaser@gmail.com',
      'password'  =>  Hash::make('demo'),
    ])->each(function($user) use ($files){
      $user->profile_picture=$files[0];
      $user->save();
      for($i=1;$i<=7;$i++){
        Hour::fetchData([
          'worker'=>$user->id,
          'day'=>$i,
          'start_hour'=>'08:00',
          'finish_hour'=>'17:00'
        ]);
      }
    });

    factory(User::class,1)->create([
      'username'=>'terapeutanutri',
      "role"=>"worker",
      'area'=>1,
      "name"=>"terapeutanutri",
      "last_name"=>"prueba",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'terapeutanutri@gmail.com',
      'password'  =>  Hash::make('demo'),
    ])->each(function($user) use ($files){
      $user->profile_picture=$files[1];
      $user->save();
      for($i=1;$i<=7;$i++){
        Hour::fetchData([
          'worker'=>$user->id,
          'day'=>$i,
          'start_hour'=>'08:00',
          'finish_hour'=>'17:00'
        ]);
      }
    });


    factory(User::class,1)->create([
      'username'=>'urbanoprogramador',
      "role"=>"worker",
      'area'=>1,
      "name"=>"prueba",
      "last_name"=>"prueba",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'urbanoprogramador@gmail.com',
      'password'  =>  Hash::make('demo'),
    ])->each(function($user) use ($files){
      $user->profile_picture=$files[3];
      $user->save();
      for($i=1;$i<=7;$i++){
        Hour::fetchData([
          'worker'=>$user->id,
          'day'=>$i,
          'start_hour'=>'08:00',
          'finish_hour'=>'17:00'
        ]);
      }
    });

    factory(User::class,1)->create([
      'username'=>'terapeuta',
      "role"=>"worker",
      'area'=>2,
      "name"=>"prueba",
      "last_name"=>"prueba",
      "phone"=>"prueba",
      "gender"=>"male",
      "email" => 'terapeuta@amsalud.com',
      'password'  =>  Hash::make('demo'),
    ])->each(function($user) use ($files){
      $user->profile_picture=$files[2];
      $user->save();
      for($i=1;$i<=7;$i++){
        Hour::fetchData([
          'worker'=>$user->id,
          'day'=>$i,
          'start_hour'=>'08:00',
          'finish_hour'=>'17:00'
        ]);
      }
    });




    
    factory(User::class,1)->create(['username'=>'users17', 'role'=>'user','email'=>'roimar.urbano@tigears.com','area'=>null])->each(function ($user) {
      $user->showOrCreateClient();
    });
    factory(User::class,1)->create([ 'role'=>'user','email'=>'cliente@amsalud.com','area'=>null])->each(function ($user) {
      $user->showOrCreateClient();
    });

    factory(User::class,1)->create(['role'=>'user','email'=>'adriana@789.mx','area'=>null,'birthday'=>'1998-09-16','name'=>'adriana'])->each(function ($user) {
      $user->showOrCreateClient();
    });
    factory(User::class,1)->create(['role'=>'user','email'=>'adrimabarak@hotmail.com','area'=>null,'birthday'=>'2008-07-16','phone'=>'+522299297200','name'=>'adriana'])->each(function ($user) {
      $user->showOrCreateClient();
    });
    factory(User::class,1)->create([ 'role'=>'user','email'=>'amelia@amsalud.com','area'=>null,'birthday'=>'1982-03-25','phone'=>'5559895224','name'=>'amelia'])->each(function ($user) {
      $user->showOrCreateClient();
    });

    //amsalud




  }

}
