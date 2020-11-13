<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    //
    protected $table="paquetes";
    protected $fillable=[
    	'credit_id',
    	'nombre',
    	'points',
    	'bonus',
    	'status'
    ];

    public function moneda(){
    	return $this->belongsTo(Credito::class,'credit_id','id');
    }
}
