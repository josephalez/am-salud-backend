<?php

namespace App\Jobs;

use App\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Mail\ConfirmReserva as ConfirmReservaMail;

class ConfirmReserva implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reserva;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reserva)
    {
        //
        $this->reserva=$reserva;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messaging = app('firebase.messaging');


        Log::info($this->reserva->myuser->topic_firebase);


        $body='Fecha: '.$this->reserva->reservation_start." De la reserva";
        $message = CloudMessage::fromArray([
            'topic' => $this->reserva->myuser->topic_firebase,
            'notification' => ['title'=>'Confirmacion de reserva', 'body'=>$body], // optional
            'data' => ['reserva_id'=>$this->reserva->id], // optional
        ]);
        $messaging->send($message);
        Mail::to($this->reserva->myuser->email)->send(new ConfirmReservaMail($this->reserva));
    }
}
