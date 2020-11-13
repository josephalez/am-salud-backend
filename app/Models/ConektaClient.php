<?php

namespace App\Models;

use App\Models\Client;
use App\Models\ConektaCard;
use Illuminate\Database\Eloquent\Model;

class ConektaClient extends Model
{
    //
    protected $table="conekta_clients";
    protected $fillable=[
    	"user_id",
    	"token_laser",
    	"token_nutry",
    ];

    public function user(){
    	return $this->belongsTo(Client::class,"user_id","id");
    }

    public function cards(){
    	return $this->hasMany(ConektaCard::class,"conekta_client_id","id");
    }
}
