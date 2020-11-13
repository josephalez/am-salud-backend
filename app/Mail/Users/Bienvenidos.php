<?php

namespace App\Mail\Users;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Bienvenidos extends Mailable
{
    use Queueable, SerializesModels;

    public $user,$client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$client)
    {
        //
        $this->user=$user;
        $this->client=$client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->client){
            return $this->subject("Bienvenido a AMSALUD")->view('email.Users.Bienvendios');
        }else{

        }
        
    }
}
