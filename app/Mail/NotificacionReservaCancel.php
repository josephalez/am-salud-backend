<?php

namespace App\Mail;

use App\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionReservaCancel extends Mailable
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
            return $this->from(env('MAIL_FROM_RESERVAS', 'contact@example.com'))->view('email.reservas.reserva_cancel');
        }else{
            return $this->from(env('MAIL_FROM_RESERVAS', 'contact@example.com'))->view('email.reservas.reserva_cancel_especialista');
        }
        
    }
}
