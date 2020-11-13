<?php

namespace App\Jobs\Users;

use App\User;
use Illuminate\Bus\Queueable;
use App\Mail\Users\Bienvenidos;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmailBienvendios implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
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


        if($this->user->role="user"){
            Mail::to($this->user->email)->send(new Bienvenidos($this->user,true));
        }else{
            Mail::to($this->user->email)->send(new Bienvenidos($this->user,false));
        }

        
    }
}
