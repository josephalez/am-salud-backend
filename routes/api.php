<?php

use App\User;
use Illuminate\Http\Request;
use App\Jobs\Users\EmailBienvendios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Messaging\CloudMessage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("users", "UsersController@register")->name('register');

Route::post("users/login", "UsersController@authenticate")->name('login.post');

Route::post("users/admin", "UsersController@adminEnter")->name('admin.login.post');

Route::get("services", "ServicesController@getAll");

Route::get("hours/{worker}", "HoursController@getSchedule");

Route::get('workers/{worker}/prices', 'Worker\WorkerPriceController@index');

Route::group(['middleware' => ['auth:api']], function () {

    Route::post("reservations/{worker}", "ReservationsController@add");

    Route::group(["middleware"=>["is_owner"]],function(){

        Route::post("hours", "HoursController@addHourRange");

    });

    Route::get('members/{service}', 'Client\MemberController@index');
    
    Route::get('expedients/{service}', 'Client\ExpedientsController@index');

    Route::get('expedients', 'Client\ExpedientsController@mine');

    Route::get('assistance/{service}', 'Client\AssistanceController@index');
 
    Route::get('assistance', 'Client\AssistanceController@mine');

    Route::get('sessions/{service}', 'Client\SessionsController@index');

    Route::get('sessions', 'Client\SessionsController@mine');

    Route::get('reservations/work', "ReservationsController@myReservations");

    Route::get('reservations/mine', "ReservationsController@mine");

    Route::delete('reservations/{reservation}', "ReservationsController@cancel");    

    Route::get('reservations/confirm/{reservation}', "ReservationsController@confirm")->name('confirm.reservations');

    Route::delete("hours/{day}", "HoursController@removeDayOff");

    Route::get("hours/get/mine", "HoursController@getMine");

    Route::put("users/edit", "UsersController@editData");

    Route::get("users/me", "UsersController@getAuthenticatedUser");

    Route::get('workers/prices', 'Worker\WorkerPriceController@myprice');

    Route::get("workers/{service}", "UsersController@getByService");
    
    Route::get('nosotros', "Personal\PersonalController@index");        
    
    Route::group(["middleware"=>["atencion"]], function(){
        Route::apiResource('nosotros', "Personal\PersonalController")->parameters(['personal'])->only(['index','store', 'update', 'destroy']);
    });
    
    Route::group(["middleware"=>["admin"]], function(){

        Route::get('workers',"UsersController@getPersonalAdded");

        Route::post("workers", "UsersController@registerPersonal")->name('registerworkers');

        Route::post("services", "ServicesController@add"); 

        Route::put("services/{id}", "ServicesController@edit");
        
        Route::delete("services/{id}", "ServicesController@remove");

        Route::apiResource('admin/categories', "Admin\CategoriesController")->only(['index','store', 'update']);

        Route::get('admin/categories/select', "Admin\CategoriesController@select");

        Route::apiResource('admin/pedidos', 'Admin\PedidosDetailController')->only(['index']);

        Route::put('admin/worker/{worker_id}', 'UsersController@editWorker');

        Route::post('checkout/{pedido_id}','CheckoutController@confirmPayment');

    });

    //Route::post('prices/{user}', "admin\PricesController@asssign");
    Route::apiResource('client','Client\ClientController')->only(['index','show','destroy']);
    Route::get('clients/all', "Client\ClientController@getAll");
    Route::apiResource('zonas','Client\ZonaLaserClientController')->parameters(['zonas'=>'zonaLaser'])->only(['index']);

    Route::apiResource('clients/stripes/card','Client\StripeCardController');
    Route::apiResource('clients/stripes','Client\StripeClientController');
    Route::apiResource('clients/balances','Client\StripeBalanceController');

    Route::post('workers/prices', 'Worker\WorkerPriceController@store');
    

    Route::apiResource('clients/prices','Admin\PricesController');
    Route::apiResource('clients.notas','NotasController');
 
    Route::apiResource('credito','Admin\CreditoController');
    Route::apiResource('users.balances','Admin\Users\UserBalanceController');
    Route::apiResource('balances.movimientos','Admin\Users\BalanceMovimientosController');

    Route::apiResource('personal', 'Client\WorkerController')->only(['index','show'])->parameters([
        'personal' => 'worker'
    ]);
    Route::apiResource('asociados','Client\AsociadoController');

    Route::apiResource('reservations.contratos','Reservations\ContratoController');
    Route::get('paquetes/saldo','PaqueteController@view_saldo');
    Route::apiResource('paquetes','PaqueteController');
    

    Route::get('products/categroia','CategoriClientController');
    Route::apiResource('products','ProductController');
    Route::apiResource('cars','CarController');
    Route::post('checkout','CheckoutController@store');

    Route::post('checkoutpaquete/admin','CheckoutPaqueteController@storeAdmin');
    Route::post('checkoutpaquete','CheckoutPaqueteController@store');

    Route::prefix('reportes/reservas')->group(function(){
        Route::get('admin','ReservasReporteController@admin');
        Route::get('worker','ReservasReporteController@worker');
    });

    Route::apiResource('conekta/client','Conekta\ClientConektaController')->only(['index']);
    Route::apiResource('conekta/client/card','Conekta\CardConektaController')->only(['index','store']);


    Route::put('clients/reservations/confirm/{reservation}','ReservationsController@confirmClient');
    Route::put('clients/reservations/cancel/{reservation}','ReservationsController@cancelClient');
    Route::get('clients/descuentos/{service}','Admin\PricesController@descuentos');

    Route::get('primeravez','ReservationsController@primeraVez');

    Route::get('clients/pedidos','Client\PedidoController');
    Route::get('devolucion/reservars/{reserva}/zona/{zona}','ReservationsController@devolucion');



});



Route::get('zonas','Admin\ZonaLaserController@index');
Route::get('demo/{demo}',function(App\User $demo){

    $messaging = app('firebase.messaging');

    Log::info($demo->topic_firebase);


    $body='Fecha: '.$demo->created_at.' Usuario '.$demo->name;

        //dd($demo->topic_firebase);
    $data=[
        'topic' => $demo->topic_firebase,
            //'topic' => 'q8E8jW8OyiAciTYjHEmBJimsJ3UyZ45nnljA4tUIpzqHi3y936',
            'notification' => ['title'=>'Confirmacion de reserva', 'body'=>$body], // optional
            'data' => ['id'=>$demo->id], // optional
        ];
        //dd($data);
        $message = CloudMessage::fromArray($data);

        $res=$messaging->send($message);

        dd($res);

        return $demo;
    });


//Demo Stripe

Route::get('stripe',function(){
    try {
        \Stripe\Stripe::setApiKey("sk_test_4eC39HqLyjWDarjtT1zdp7dc");

        $charge = \Stripe\Charge::create([
          "amount" => 2000,
          "currency" => "usd",
  "source" => "tok_mastercard", // obtained with Stripe.js
  "description" => "My First Test Charge (created for API docs)"
], [
  "idempotency_key" => "ErN9KYADezO8ZmvI",
]);
        return json_encode($charge);
    } catch (Exception $e) {
        //dd($e);
        $aste="***************";
        $aste.=$aste.$aste;
        Log::channel('stripe')->info($aste.' Fecha '.now()." ".$aste);
        //dd(Auth::check());
        $data=[
            "HttpStatus"=>$e->getHttpStatus(),
            "JsonBody"=>$e->getJsonBody(),
            "Message"=>$e->getMessage(),
            "Error"=>$e->getError(),
            "user_Info"=>(Auth::check())?Auth::user():null
        ];
        Log::channel('stripe')->info($data);
        log::Channel('stripe')->info($aste." Fin ".$aste);
    }

})->middleware();


Route::get('enviar/mail/{user}',function($user){
    

    $user=User::find($user);
    EmailBienvendios::dispatchNow($user);
    echo "email enviado al usuario ".$user->email;
});


