<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCredito extends Model
{
    //
    protected $table="movimiento_creditos";
    protected $fillable=[
    	'balance_id',
    	'payment_id',
    	'worker',
    	'monto',
        'pago_stripe_id'
    ];
    public function worker(){
    	return $this->belongsTo(User::class,'worker','id');
    }
    public function balance(){
    	return $this->belongsTo(BalanceCredito::class,'balance_id','id');
    }
}
