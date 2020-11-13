<?php 

namespace App\Traits;

use PDO;
use Conekta\Order;
use Conekta\Conekta;
use Conekta\Customer;
use App\Models\ConektaCard;
use App\Models\ConektaClient;
use Illuminate\Support\Facades\DB;
use Conekta\ParameterValidationError;

trait ConektaTrait{

	public function saldoVirtual($monto,$idMoneda){

		$sql="SELECT * FROM `mis_paquetes` where user_id=? and credit_id=? and `status`=0";

		$sql2="UPDATE `mis_paquetes` SET restante=? WHERE id=?";
		$sql3="UPDATE `mis_paquetes` SET restante=? , `status`=1 WHERE id=?";

		$pdo = DB::getPdo();
		$sentencia=$pdo->prepare($sql);
		$sentencia->execute([$this->id , $idMoneda]);

		//abort(409, json_encode([$this->id,$idMoneda]));


		$filas=$sentencia->fetchAll(PDO::FETCH_ASSOC);


		//abort(409, json_encode($filas));

		foreach ($filas as $key => $row) {
			$descontar=$row["saldo"]-$row["restante"];
			if($descontar>$monto){
				$row["restante"]+=$monto;
				$monto=0;
				$sentencia=$pdo->prepare($sql2);
				$sentencia->execute([ $row["restante"] , $row["id"] ]);
				break;
			}else{
				$monto-=$descontar;
				$row["restante"]+=$row["saldo"];

				$sentencia=$pdo->prepare($sql3);

				$sentencia->execute([ $row["restante"] , $row["id"] ]);
			}
		}




	}

	private function setKeyConecta($key){
		Conekta::setApiKey($key);
		\Conekta\Conekta::setApiVersion("2.0.0");
	}

	public function showOrCreateClient(){
		if($this->conekta){
			return $this->conekta;
		}
		$client=[
			'name'=>$this->name,
			'email'=>$this->email,
			'phone'=>$this->phone
		];
		$clientlaser=$this->createCustomer($client,config('services.conekta.laserKey'));
		$clientnutry=$this->createCustomer($client,config('services.conekta.nutryKey'));

		$conekta=$this->conekta()->save(
			new ConektaClient(['token_laser' => $clientlaser["id"],'token_nutry'=>$clientnutry["id"]])
		);
		return $conekta;
	}

	private function createCustomer($param,$key=false){
		if($key){
			$this->setKeyConecta($key);
		}
		try{
			return Customer::create($param);
		}catch (Exception $e) {
			abort(409, $error->getMessage());
		}catch (ParameterValidationError  $error){
			abort(409, $error->getMessage());
		}
	}
	private function GetClient($token,$key=false){
		if($key){
			$this->setKeyConecta($key);
		}
		try{
			return Customer::find($token);
		}catch (ParameterValidationError  $error){
			abort(409, $error->getMessage());
		}
	}


	public function createOrder($items,$card,$type,$shippin_addres=false){
		try{

			/*
			"shipping_contact" => [
				"address" => [
					"street1" => "Calle 123, int 2",
					"postal_code" => "06100",
					"country" => "MX"
				]
			],
			*/
			
			$card=ConektaCard::find($card);

			switch ($type) {
				case 'laser':

				$cardId=$card->token_src_laser;
				$customer=$this->conekta->token_laser;
				$this->setKeyConecta(config('services.conekta.laserKey'));
				break;
				case 'nutry':

				$cardId=$card->token_src_nutry;
				$customer=$this->conekta->token_nutry;
				$this->setKeyConecta(config('services.conekta.nutryKey'));
				break;


				default:
					# code...
				return false;
				break;
			}


			$orderDarta=[
				"line_items" => $items,
				"currency" => "MXN",
				'customer_info' => [
					'customer_id' => $customer
				],
				"metadata" => ["reference" => "12987324097", "more_info" => "lalalalala"],
				"charges" => [
					[
						"payment_method" => [
							"type" => "card",
							"payment_source_id" => $cardId
						]
					]
				]
			];
			
			if($shippin_addres){
				$orderDarta["shipping_contact"]=$shippin_addres;
			}
			return $order=Order::create($orderDarta)->id;
		} catch (\Conekta\ProcessingError $error){
			abort(409, $error->getMessage());
		} catch (\Conekta\ParameterValidationError $error){
			abort(409, $error->getMessage());
		} catch (\Conekta\Handler $error){
			abort(409, $error->getMessage());
		}
	}
	public function createCard($tokenLaser,$tokenNutry){

		try{
			$client=$this->GetClient($this->conekta->token_laser,config('services.conekta.laserKey'));
			$sourceLaser=$client->createPaymentSource([
				'token_id' => $tokenLaser,
				'type'     => 'card'
			]);
		}catch (\Conekta\ProcessingError $error){
			abort(409, "laser token ".$error->getMessage());
		} catch (\Conekta\ParameterValidationError $error){
			abort(409, "laser token ".$error->getMessage());
		} catch (\Conekta\Handler $error){
			abort(409, "laser token ".$error->getMessage());
		}


		try{
			$client2=$this->GetClient($this->conekta->token_nutry,config('services.conekta.nutryKey'));

			$sourceNutry=$client2->createPaymentSource([
				'token_id' => $tokenNutry,
				'type'     => 'card'
			]);
		}catch (\Conekta\ProcessingError $error){
			abort(409, "nutry token ".$error->getMessage());
		} catch (\Conekta\ParameterValidationError $error){
			abort(409, "nutry token ".$error->getMessage());
		} catch (\Conekta\Handler $error){
			abort(409, "nutry token ".$error->getMessage());
		}


		return [
			"sourceLaser"=>$sourceLaser->id,
			"sourceNutry"=>$sourceNutry->id
		];
	}

}