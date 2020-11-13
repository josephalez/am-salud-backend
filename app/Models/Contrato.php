<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Contrato extends Model
{
    //
    protected $table="contratos";
    protected $fillable=[
    	'reservation_id',
		'file_contrato',
		'operador'
    ];


    public function getFileContratoAttribute($value)
    {
        return secure_asset(Storage::url($value));
    }

}
