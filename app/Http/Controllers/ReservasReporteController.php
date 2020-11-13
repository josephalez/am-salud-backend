<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservasReporteController extends ApiController
{
    //
	public function admin(){
		$sql="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>? GROUP by servicio_id, mes ORDER BY mes DESC";
		$sql2="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>? and status='pagada' GROUP by servicio_id, mes ORDER BY mes DESC";
		$sql3="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>? and status='cancelada' GROUP by servicio_id, mes ORDER BY mes DESC";
		$sqlv="SELECT SUM(monto) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>? and status='pagada' GROUP by servicio_id, mes ORDER BY mes DESC";
		$sqlWorkers="SELECT SUM(monto) AS total, worker, servicio_id,  users.name AS workerName FROM reservations INNER JOIN users ON reservations.worker = users.id WHERE status='pagada' GROUP by worker, servicio_id, users.name ";

		$res = DB::select($sql, ['2020-03-01']);
		$res2 = DB::select($sql2, ['2020-03-01']);
		$res3 = DB::select($sql3, ['2020-03-01']);
		$resv = DB::select($sqlv, ['2020-03-01']);
		$resWorkers = DB::select($sqlWorkers, ['2020-03-01']);		

		return $this->showArray([
			'general'=>$res, 
			'confirmadas'=>$res2,
			'canceladas'=>$res3, 
			'ventas'=>$resv,
			'workers'=>$resWorkers
		]);
	}
	public function worker(){
		$sql="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>'2020-03-01' and worker=:user_id GROUP by servicio_id, mes ORDER BY mes DESC";
		$sql2="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>'2020-03-01' and worker=:user_id and status='pagada' GROUP by servicio_id, mes ORDER BY mes DESC";
		$sql3="SELECT COUNT(*) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>'2020-03-01' and worker=:user_id and status='cancelada' GROUP by servicio_id, mes ORDER BY mes DESC";
		$sqlv="SELECT SUM(monto) AS total ,servicio_id , MONTH(reservation_start) mes FROM `reservations` WHERE reservation_start>'2020-03-01' and worker=:user_id and status='pagada' GROUP by servicio_id, mes ORDER BY mes DESC";

		$res = DB::select($sql, ["user_id"=>Auth::user()->id]);
		$res2 = DB::select($sql2, ["user_id"=>Auth::user()->id]);
		$res3 = DB::select($sql3, ["user_id"=>Auth::user()->id]);
		$resv = DB::select($sqlv, [ "user_id"=>Auth::user()->id]);		

		return $this->showArray([
			'general'=>$res, 
			'confirmadas'=>$res2, 
			'canceladas'=>$res3,
			"ventas"=>$resv
		]);
	}

}
