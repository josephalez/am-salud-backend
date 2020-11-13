<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerPrice extends Model
{

    protected $fillable = [
        "worker",
        "tarifa",
        "minutes",
        "descripcion"
    ];

    public function worker(){
        return $this->belongsTo(Worker::class,'worker','id');
    }

}
 