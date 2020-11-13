<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{ 
    //
    protected $table="pedidos";
    protected $fillable=[
    	"user_id",
        "status",
        "sent",
        "domicilio",
    	"total",
    	"payment_id",
        "pago_stripe_id",
        "pedido_code",
        "pedido_code",
        "calle",
        "numExt",
        "numInt",
        "cp",
        "colonia",
        "municipio",
    ];

    public static function boot() {
        parent::boot();
    
        static::creating(function (Pedido $item) {
            $item->pedido_code = mt_rand(100000, 999999);
        });
    
    }

    public function detalle(){
    	return $this->hasMany(PedidoDetalle::class,'pedido_id','id');
    }
}
