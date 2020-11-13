<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Reservation;
use App\Jobs\ConfirmReserva;
use Illuminate\Bus\Queueable;
use App\Mail\NotificacionReserva;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Demo implements ShouldQueue
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
        Mail::to($this->reserva->myuser->email)->send(new NotificacionReserva($this->reserva));
        Mail::to($this->reserva->myworker->email)->send(new NotificacionReserva($this->reserva,false));
        //
        

        

        Log::channel('stripe')->error($this->reserva->reservation_start);


        //$remember=Carbon::createFromFormat('Y-m-d H:i:00',$this->reserva->reservation_start);
        //Log::channel('stripe')->error($remember->subDays(6));


        //ConfirmReserva::dispatch($this->reserva)->onQueue('confirm')->delay($remember->subDays(6));
    }
}
