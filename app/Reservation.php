<?php

namespace App;

use App\Service;
use App\Jobs\Demo;
use App\Models\Asociado;
use App\Models\ZonaReserva;
use App\Mail\NotificacionReserva;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionReservaCancel;
use Illuminate\Database\Eloquent\Model;

use App\Models\LaserForm;


class Reservation extends Model
{
    
    protected $fillable=[
        "user",
        "worker",
        "payment_id",
        "reservation_start",
        "reservation_end",
        'servicio_id',
        "monto",
        'pago_stripe_id',
        'asociado',
        'pagado',
        'descuento',
        'total'
    ];

    protected $guarded=[
        "canceled",
        "cancel_time",
        "confirmed"
    ];
    
    public function myuser(){
        return $this->belongsTo(User::class,'user','id');
    }

    public function myworker(){
        return $this->belongsTo(User::class,'worker','id');
    }

    public function asociado(){
        return $this->belongsTo(Asociado::class,'asociado','id');
    }

    public function zonas(){
        return $this->hasMany(ZonaReserva::class, "reservation", "id");
    }

    public function laserForm(){
    	return $this->hasOne(LaserForm::class,'reservation','id');
    }

    public function servicio(){
        return $this->belongsTo(Service::class,'servicio_id','id');
    }

    protected static function booted()
    {
        static::created(function ($reserva) {
            Demo::dispatchNow($reserva);
        });
        static::updated(function ($reserva){
           /* if($reserva->canceled==1){
                Mail::to($reserva->myuser->email)->send(new NotificacionReservaCancel($reserva));
                Mail::to($reserva->myworker->email)->send(new NotificacionReservaCancel($reserva,false));
            }*/
        });
    }
}
