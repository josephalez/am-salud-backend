<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Paquete;
use App\Models\MiPaquete;
use App\Models\StripeCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutPaqueteController extends ApiController
{
    //
    public function storeAdmin(Request $request){

		$request->validate([
			//'paquete_id'       => 'required',
			'saldo' => 'required',
			'monto' => 'required',
			//'credit_id'=>'required'

		]);
		$user=Auth::user();
		$monto=$request->monto;
		$saldo=$request->saldo;
		$orderid="";
		$typepayment=1;

		$credit_id=$request->credit_id;
		$paquete_id=null;
		$userid=$request->user_id;
		if($request->has("paquete_id")){
			
			$paquete_id=$request->paquete_id;
			$paquete=Paquete::find($request->paquete_id);
			
			if($paquete->points!=$monto){
				return $this->errorResponse("esta consulta esta mal echa bonus $monto",403);
			}
			$bonus=($paquete->bonus*$paquete->points/100)+$paquete->points;
			if($bonus!=$saldo){
				return $this->errorResponse("esta consulta esta mal echa saldo $saldo ",403);
			}
			if($paquete->credit_id!=$request->credit_id){
				return $this->errorResponse("el tipo de credito no coincide con el paquete ",403);
			}
		}





          $paquete=MiPaquete::create([
          	'user_id'=>$userid,
          	'paquete_id'=>$paquete_id,
          	'payment_id'=>1,
          	'pago_stripe_id'=>"",
          	'saldo'=>$saldo,
          	'monto'=>$monto,
          	'restante'=>0,
          	'credit_id'=>$credit_id,
          	'worker'=>Auth::user()->id
          ]);

          return $this->showArray($paquete);
      }




	public function store(Request $request){

		$request->validate([
			//'paquete_id'       => 'required',
			'saldo' => 'required',
			'monto' => 'required',
			//'credit_id'=>'required'
		]);
		$user=Auth::user();
		$monto=$request->monto;
		$saldo=$request->saldo;
		$orderid="";
		$typepayment=1;
		$credit_id="";
		$credit_id=$request->credit_id;

		$paquete_id=null;
		$userid=null;
		if($request->has("paquete_id")){
			$userid=$user->id;
			$paquete_id=$request->paquete_id;
			$paquete=Paquete::find($request->paquete_id);
			if($paquete->points!=$monto){
				return $this->errorResponse("esta consulta esta mal hecha bonus $monto",403);
			}
			$bonus=($paquete->bonus*$paquete->points/100)+$paquete->points;
			if($bonus!=$saldo){
			}
			if($paquete->credit_id!=$request->credit_id){
				return $this->errorResponse("el tipo de credito no coincide con el paquete ",403);
			}
			$type="";
			switch ($paquete->moneda->name) {
				case 'AM CASH':
				$type="nutry";
				break;
				case 'LC CASH':
				$type="laser";
				break;
				default:
				if($bonus!=$saldo){
					return $this->errorResponse("esta consulta esta mal echa ",403);
				}
				break;
			}
			if($request->has("typepayment")){
				if(!$request->has("card_id")){
					return $this->errorResponse("Seleccione una tarjeta de credito",403);
				}
				$credit_id=$request->credit_id;
				$typepayment=$request->typepayment;
				$item=[[
					"name" => "Compra de paquete ".$paquete->moneda->name,
					"unit_price" => intval($monto*100),
					"quantity" => 1
				]];
				$orderid=$user->createOrder($item,$request->card_id,$type);
			}
		}else{


		}




		

/*		$cargo=$charge = Charge::create([
			'amount' => $request->monto,
			'currency' => 'usd',
			'description' => 'Cargo por compra de saldo virtual ',
          	'customer' => $user->stripeid->stripe_id, //'cus_HWz4puOk5o8Hka',//$card->stripe_card_id
          	'source'=>$card->stripe_card_id
          ]);
*/



          $paquete=MiPaquete::create([
          	'user_id'=>$userid,
          	'paquete_id'=>$paquete_id,
          	'payment_id'=>$typepayment,
          	'pago_stripe_id'=>$orderid,
          	'saldo'=>$saldo,
          	'monto'=>$monto,
          	'restante'=>0,
          	'credit_id'=>$credit_id
          ]);

          return $this->showArray($paquete);
      }
  }
