<?php

namespace App\Models;

use App\Models\MovimientoCredito;
use Illuminate\Database\Eloquent\Model;

class BalanceCredito extends Model
{
    //
    protected $table="balance_creditos";
    protected $fillable=[
    	'user_id',
    	'credit_id',
    	'st',
    	'saldo'
    ];

    public function client(){
    	return $this->belongsTo(Client::class,'user_id','id');
    }
    public function movimientos(){
    	return $this->hasMany(MovimientoCredito::class,'balance_id','id');
    }

    public function credito(){
        return $this->belongsTo(Credito::class,'credit_id','id');
    }
}
