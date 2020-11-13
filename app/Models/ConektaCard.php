<?php

namespace App\Models;

use App\Models\ConektaClient;
use Illuminate\Database\Eloquent\Model;

class ConektaCard extends Model
{
    //
    protected $table="conekta_card";
    protected $fillable=[
    	"conekta_client_id",
    	"token_card_laser",
    	"token_card_nutry",
    	"token_src_laser",
    	"token_src_nutry",
    	"card_number",
    	"brand"
    ];
    public function inputs(){
    	return $this->fillable;
    }
    public function conektaClient(){
    	return $this->belongsTo(ConektaClient::class,"conekta_client_id","id");
    }
}
