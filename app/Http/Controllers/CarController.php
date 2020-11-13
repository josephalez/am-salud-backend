<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Product\CarRequest;

class CarController extends ApiController
{
    //
	public function index(Request $request)
    {
    	$user=Auth::user();
    	$cars=$user->load('cars')->cars;
    	return $this->showArray($cars);
        //return $this->paginateall(new $user->load('cars') ,$request); 
    }

    public function store(CarRequest $request){

    	$user=Auth::user();
    	$rest=$user->load(['cars' => function ($query) use ($request) {
		    $query->where('product_id', $request->product_id);
		}])->cars;


    	if(count($rest)>0){
            if($request->has("no_sumar")){
                if($request->cantidad<1){
                    $user->cars()->detach($request->product_id);
                }else{
                    $user->cars()->updateExistingPivot($request->product_id,['cantidad'=>$request->cantidad]);
                }
                
            }else{
                $cant= $rest[0]->pivot->cantidad + $request->cantidad;
                $user->cars()->updateExistingPivot($request->product_id,['cantidad'=>$cant]);
            }
    		
    	}else{
    		$user->cars()->attach($request->product_id,['cantidad'=>$request->cantidad]);
    	}
    	return $this->showArray($user->load('cars')->cars);
    }
}
