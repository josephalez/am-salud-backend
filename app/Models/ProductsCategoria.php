<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsCategoria extends Model
{
    protected $table="products_categoria";

    protected $fillable=[
        "product_id",
        "categoria_id",
    ];
}
