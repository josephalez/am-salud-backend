<?php

namespace App\Mail\Pedido;

use App\User;
use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Pendiente extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido,$user;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_RESERVAS', 'contact@example.com'))->subject("Pedido HEALTHY CORNER")->view('email.Pedidos.PedidoProceso');
    }
}
