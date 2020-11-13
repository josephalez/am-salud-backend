<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Categoria;

use App\Http\Requests\CategoryStoreRequest;

class CategoriesController extends ApiController
{
    
    public function index(Request $request){
        
        $filter=function ($query) use($request){
            if($request->sub_id){
                $query->where("sub_id", "=", $request->sub_id)
                ->join('categories', 'id', 'sub_id');
            }
        };

        $result= Categoria::where($filter)->with("subCategory");

        return $this->paginateWithQuery( $result, $request);
    }

    public function select(Request $request){

        $result= Categoria::where("sub_id","=", null);

        return $this->paginateWithQuery( $result, $request);
    }

    public function store(CategoryStoreRequest $request){

        $data=$request->all();

        $category = Categoria::create($data);
        
        if(!$category){    
            return response()->json(["error"=>"Error en la base de datos"],500);                    
        }

        return response()->json(["message"=>"Categoría registrada"],201);

    }

    public function update(CategoryStoreRequest $request, $category_id){

        $data=$request->only([
            'name',
            'description',
            'sub_id',
        ]);

        $category=Categoria::find($category_id);

        if(!$category){
            return response()->json(['message'=>'Categoría no encontrada'],404);
        }

        if(!$category->update($data)){
            return response()->json(["error"=>"Error en la base de datos"],500);            
        }

        return response()->json(200);

    }

}
