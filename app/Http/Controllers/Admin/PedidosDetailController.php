<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Pedido;

class PedidosDetailController extends ApiController
{
    
    public function index(Request $request){

        $result = Pedido::with(['detalle'=>function($q){
            $q->join('products', "products.id", "product_id")
            ->select('products.name as product_name', 'pedidos_detalles.*');
        }])->join('users', "users.id", "user_id")
        ->join('type_payments', "type_payments.id", "payment_id")
        ->where(function ($q) use($request){
            if($request->keyword){
                $q->where('pedido_code', 'LIKE', "%{$request->keyword}%")
                ->orWhere('users.name', 'LIKE', "%{$request->keyword}%")
                ->orWhere('users.last_name', 'LIKE', "%{$request->keyword}%");          
            }
        })->select('users.name as user_name', 'type_payments.name as pago_name', 'pedidos.*');

        return $this->paginateWithQuery( $result, $request);
    }

}
