<?php

namespace App\Models;

use App\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asociado extends Model
{
    //
    use SoftDeletes;
    const genero=["masculino","femenino"];
    protected $table="asociados";
    protected $fillable=[
    	'user_id', 
    	'email',
    	'name',
    	'birthday',
    	'genero'
    ];

    public function reservations(){
        return $this->hasMany(Reservation::class,"asociado","id");
    }
}
