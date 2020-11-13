<?php

namespace App;

use App\Service;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    
    protected $fillable=[
        "user",
        //"worker",
        "service",
        "price",
    ];

    public function client(){
    	return $this->belongsTo(Client::class,'user','id');
    }

    public function service(){
    	return $this->belongsTo(Service::class,'service','id');
    }

    //no me queda claro quien debe poner los precios especiales asi que por ahora se la accino a cual quier rol de usuario
    public function worker(){
    	return $this->belongsTo(User::class,'worker','id');
    }

}
