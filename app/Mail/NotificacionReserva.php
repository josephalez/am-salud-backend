<?php

namespace App\Mail;

use App\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionReserva extends Mailable
{
    use Queueable, SerializesModels;


    public $reserva,$cliente;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reserva,$cliente=true)
    {
        //
        $this->reserva=$reserva;
        $this->cliente=$cliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->cliente){
            if($this->reserva->servicio_id==1){
                return $this->subject("Reserva nutricion")->view('email.reservas.nutricion');
            }else{
                return $this->subject("Reserva Laser")->view('email.reservas.laser');
            }
        }else{
            return $this->view('email.reservas.notificacion_especialista');
        }
        
    }
}
