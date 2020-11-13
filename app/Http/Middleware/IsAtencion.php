<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class IsAtencion
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
        
        if($user->role!='atencion'&&$user->role!='admin'){
            return response()->json(["error"=>"No tienes Permiso"],403);
        }
                
        return $next($request);
    }
}
    