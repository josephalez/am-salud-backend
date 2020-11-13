<?php

namespace App\Http\Controllers\Worker;


use Auth;
use App\Models\Worker;
use App\Models\WorkerPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\WorkerPriceStoreRequest;

class WorkerPriceController extends ApiController
{
    public function index(Worker $worker){
        $price = $worker->prices;

        return response()->json($price);
    }

    public function myprice(){
        $price= Auth::user()->authprices;

        return response()->json($price);
    }

    public function store(WorkerPriceStoreRequest $request){

        //$request->validate();

        $data=$request->all();

        $data['worker']= Auth::user()->id;

        //$price=WorkerPrice::where("worker","=",$data['worker'])->first();   

        $price = WorkerPrice::create($data);


        /*if(!$price){
            $price = WorkerPrice::create($data);
            
            if(!$price) return response()->json(["error"=>"Error en la base de datos"],500);                    
        }
        else{
            if(!$price->update($data)) return response()->json(["error"=>"Error en la base de datos"],500);        
        }*/

        return response()->json(["message"=>"Tarifa y duración modificadas"],201);

    }
    public function update(Request $request,$id){

    }

}
