<?php

namespace App\Http\Controllers;

use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Pedido;
use App\Models\StripeCard;
use Illuminate\Http\Request;
use App\Models\PedidoDetalle;
use App\Jobs\Pedido\Pendiente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Product\ChectoutStore;

class CheckoutController extends ApiController
{
    //
    public function store(ChectoutStore $request,StripeCard $card){
    	DB::beginTransaction();

    	$user=Auth::user();
    	$cars=$user->load('cars')->cars;

    	$pedido=new Pedido;

    	$pedido->user_id=$user->id;

    	$pedido->payment_id=$request->typepayment;

    	$errorMensaje=array(
    		"error_stock"=>array(),
    	);
    	$error=false;

    	$mount=0;

    	$pedido->status=0;
    	$pedido->total=0;

    	$pedido->save(); 
        $item=[];
        foreach ($cars as $key => $value) {
              $costo=$value->pivot->cantidad*$value->price;
              $mount+=$costo;
              if($value->stock<$value->pivot->cantidad){
                 $errorMensaje["error_stock"][]=$value;
                 $error=true;
             }else{
                 $value->stock-=$value->pivot->cantidad;
                 $value->save();
             }
             $pedido->detalle()->save(new PedidoDetalle([
                 'product_id'=>$value->id,
                 'cantidad'=>$value->pivot->cantidad,
                 'price'=>$value->price
             ]));
             $item[]=[
                "name" => "Producto ".$value->name,
                "unit_price" => intval($value->price*100),
                "quantity" => $value->pivot->cantidad
            ];

        }

        if($error){
            DB::rollBack();
            return $this->errorResponse($errorMensaje,500);
        }

        $user->cars()->sync([]);
        $orderid="";
        if($request->typepayment=="3"){
            $orderid=$user->createOrder($item,$request->card_id,"nutry");
        }

        if($request->typepayment=="2"){
            $user->saldoVirtual($mount,1); 
        }

        if($request->has('calle')){
            $pedido->calle=$request->calle;
        }
        if($request->has('numExt')){
            $pedido->numExt=$request->numExt;
        }
        if($request->has('numInt')){
            $pedido->numInt=$request->numInt;
        }
        if($request->has('cp')){
            $pedido->cp=$request->cp;
        }
        if($request->has('colonia')){
            $pedido->colonia=$request->colonia;
        }
        if($request->has('municipio')){
            $pedido->municipio=$request->municipio;
        }


        $pedido->total=$mount;
        $pedido->pago_stripe_id = $orderid;
        $pedido->save();

        Pendiente::dispatchNow($pedido,$user);

        DB::commit();
        return $this->showArray(["ok"]);


    }

    public function confirmPayment($pedido_id){

        $pedido= Pedido::find($pedido_id);

        if(!$pedido) return $this->errorResponse(['error'=>"Pedido no encontrado"],404);

        if($pedido->status==0) $pedido->status=1;

        else if($pedido->status==1&&$pedido->sent==0) $pedido->sent=1;
        
        else return $this->showArray(["ok"]);

        if(!$pedido->save()) return $this->errorResponse(["error"=>"Error en la base de datos"],500);

        return $this->showArray(["ok"]);

    }

}
