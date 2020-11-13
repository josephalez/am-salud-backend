<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $table="categorias";
    protected $fillable=[
    	'name',
    	'description',
    	'sub_id',
    ];


    public function productos(){
    	return $this->belongsToMany(Product::class,'products_categoria','categoria_id','product_id');
    }

    public function subCategory(){
        return $this->belongsTo(Categoria::class, 'sub_id');
    }

    
}
