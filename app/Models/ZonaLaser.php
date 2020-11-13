<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZonaLaser extends Model
{
    //
    use SoftDeletes;
    protected $table='zona_lasers';
    protected $fillable=['name' ,'completo','retoque','time_completo','time_retoque'];
}
