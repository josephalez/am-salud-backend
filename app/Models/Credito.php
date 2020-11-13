<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    //
    protected $table="creditos";
    protected $fillable=[
    	'name',
    	'description'
    ];
}
