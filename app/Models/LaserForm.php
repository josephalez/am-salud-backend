<?php

namespace App\Models;

use App\Reservation;

use Illuminate\Database\Eloquent\Model;

class LaserForm extends Model
{

    protected $table= "laser_forms";    
    
    protected $fillable=[
        "reservation",
        "sun",
        "medical",
        "radiation",
        "sensible_skin",
        "hormonal",
        "external_product", 
        "menstruation",
        "date",
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class, 'reservation', 'id');
    }

}
