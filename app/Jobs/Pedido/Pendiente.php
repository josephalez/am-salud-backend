<?php

namespace App\Jobs\Pedido;

use App\User;
use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Pedido\Pendiente as mailpedido;

class Pendiente implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $pedido;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pedido $pedido,User $user)
    {
        //
        $this->pedido=$pedido;
        $this->user=$user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $bcc=config('mail.bcc');

        Mail::to($this->user->email)->bcc($bcc)->send(new mailpedido($this->pedido,$this->user));
    }
}
