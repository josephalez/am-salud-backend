<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiPaquete extends Model
{
    //
    protected $table="mis_paquetes";
    protected $fillable=[
    	'user_id',
    	'paquete_id',
    	'payment_id',
        'credit_id',
    	'worker',
    	'pago_stripe_id',
    	'saldo',
    	'monto',
    	'restante',
    ];
    public function paymante(){
    	return $this->belongsTo(TypePayments::class,'payment_id','id');
    }
    public function myworker(){
    	return $this->belongsTo(Worker::class,'worker','id');
    }
    public function paquete(){
    	return $this->belongsTo(Paquete::class,'paquete_id','id');
    }
}
