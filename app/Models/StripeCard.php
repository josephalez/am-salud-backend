<?php

namespace App\Models;

use App\Models\StripeClient;
use Illuminate\Database\Eloquent\Model;

class StripeCard extends Model
{
    //
    protected $table="stripe_cards";
    protected $fillable=["stripe_client_id","stripe_card_id","card_number"];

    public function clientStripe(){
    	return $this->belongsTo(StripeClient::class,'stripe_client_id','id');
    }
}
