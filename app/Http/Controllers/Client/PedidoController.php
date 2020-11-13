<?php

namespace App\Http\Controllers\Client;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class PedidoController extends ApiController
{
    //
    public function __invoke(Request $request)
    {
    	$user=Auth::user()->id;
    	$misPedidos=Pedido::with(['detalle.producto'])->where('user_id',$user)->orderBy('id','desc')->get();
    	return $this->showArray($misPedidos);
    }
}
