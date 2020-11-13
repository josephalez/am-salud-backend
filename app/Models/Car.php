<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //
    protected $table="cars";
    protected $fillable=[
    	'user_id',
    	'product_id',
    	'cantidad',
    ];
}
