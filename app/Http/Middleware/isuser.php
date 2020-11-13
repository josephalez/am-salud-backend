<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isuser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=Auth::user();
        //dd($user);
        if(($user->role!='user')){

            return response()->json(["error"=>"No tienes Permisos"],403);

        }

        //$user=User::find($request->input("worker"));

        //if($user&&$user->role!="worker") return response()->json(['error'=>"Solo el personal puede actualizar su horario"],400);
        
        return $next($request);
    }
}
