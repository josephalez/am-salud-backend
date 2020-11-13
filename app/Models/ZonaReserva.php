<?php

namespace App\Models;

use App\Reservation;
use Illuminate\Database\Eloquent\Model;

class ZonaReserva extends Model
{
    
    protected $table= "zones_reservations";

    protected $fillable=[
        "reservation",
        "zone",
        "status",
        "is_retoque",
        "monto_zona",
    ];
    
    public function reservation(){
        $this->belongsToMany(Reservation::class, 'reservation', 'id');
    }

}
