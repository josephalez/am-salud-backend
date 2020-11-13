<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class StripeClient extends Model
{
    //
    protected $table="stripe_clients";
    protected $fillable=["stripe_id","user_id","object"];

    public function user(){
    	return $this->belongsTo(Client::class,"user_id","id");
    }
    public function card(){
    	return $this->hasMany(StripeCard::class,'stripe_client_id','id');
    }
}
