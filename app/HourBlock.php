<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HourBlock extends Model
{
    
    protected $fillable=[
        'hour',
        'start',
        'end',
    ];

}
