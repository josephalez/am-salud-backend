<?php

namespace App\Models;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    //
    protected $table="products";

    protected $fillable=[
    	"main_picture",
    	"picture_uno",
    	"picture_dos",
    	"name",
    	"description", 
    	"stock",
    	"price"
    ];

    public function inputs(){
    	return $this->fillable;
    }


    public function getMainPictureAttribute($value)
    {
        return secure_asset(Storage::url($value));
    }
    public function getPictureUnoAttribute($value)
    {
        return ($value) ? secure_asset(Storage::url($value)) : null;
    }
    public function getPictureDosAttribute($value)
    {
        return ($value) ? secure_asset(Storage::url($value)) : null;
    }


    public function categorias(){
        return $this->belongsToMany(Categoria::class ,'products_categoria','product_id','categoria_id');
    }
}
