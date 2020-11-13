<?php

namespace App\Models;

use App\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    //
    protected $table="notas";
    protected $fillable=['client','worker','nota'];

    public function client(){
    	return $this->belongsTo(Client::class,'client','id');
    }
    public function worker(){
    	return $this->belongsTo(User::class,'worker','id');
    }
}
