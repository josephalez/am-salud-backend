<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    //
    protected $table="pedidos_detalles";
    protected $fillable=[
    	'pedido_id',
    	'product_id',
    	'cantidad',
    	'price'
    ];

    public function producto(){
    	return $this->belongsTo(Product::class,'product_id','id');
    }
}
