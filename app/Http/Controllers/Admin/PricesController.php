<?php

namespace App\Http\Controllers\Admin;

use JWTAuth;
use App\User;
use App\Price;
use App\Service;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StorePriceRequest;

class PricesController extends ApiController
{
    public function descuentos(Request $request ,$service){

        $sql="SELECT max(price) as price  FROM `prices` WHERE (prices.user=? or prices.user IS null) and service=?";

        $user=Auth::user()->id;



        //return $this->showArray([$user,$service]);
        $users = DB::select($sql, [$user,$service]);



        if(is_null($users[0]->price)){
            return $this->showArray(0);
        }
        return $this->showArray($users[0]->price+0);

    }
    public function index(Request $request){

        $result= Price::with(['client', 'service']);

        return $this->paginateWithQuery( $result, $request);
    }

    public function update(){

    }
    public function delete(){

    }

    
    public function store(StorePriceRequest $request){

        $data= $request->all();        

        $worker= User::find(JWTAuth::user()->id);

        if($worker->role!="worker"&&$worker->role!="admin") return response()->json(["error"=>"No tienes permisos para asignar precios especiales"],403);

        $service=null;

        if($worker->role=='worker') $service= Service::find($worker->area);

        else if($worker->role=='admin') $service= Service::find($request->input('service'));

        if(!$service) return response()->json(["error"=>"No hay un servicio asignado"],404);

        if($request->input('user')){
            
            $user= User::find($request->input('user'));

            if($user) 
            {
                $data["user"]=$user->id;
                if($user->id===$worker->id) return response()->json(["error"=>"No puedes asignarte tus propios precios"],400);
            }

            else return response()->json(["error"=>"Usuario no encontrado"],404);

        }


        //$data["worker"]=$worker->id;
        $data["service"]=$service->id;

        $price= null;

        if($request->input('user')){   
            $price=Price::where('user',"=",$data["user"])
            ->where('service',"=",$data["service"])->first();
        }else {
            $price= Price::where('service',"=",$data["service"])
            ->where('user', '=', null)->first();
        }

        if(!$price){
            $price=Price::create($data);
            if(!$price) return response()->json(["error"=>"Error en la base de datos"],500);
        }

        else{
            if(!$price->update($data)) return response()->json(["error"=>"Error en la base de datos", "data"=>$data],500);
        }
        return response()->json(["message"=>"Precio especial asignado exitosamente"],200);

    }

}
