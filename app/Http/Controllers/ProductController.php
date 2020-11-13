<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Categoria;
use App\Models\ProductsCategoria;
use Illuminate\Http\Request;
use App\Helpers\General\CollectionHelper;
use App\Http\Requests\Product\ProducStore;
use App\Http\Requests\Product\ProducUpdate;


class ProductController extends ApiController
{
    //
	public function index(Request $request)
    {

        $pag=($request->per_page) ? $request->per_page:15;

        $id='id';
  

//        $paginate = new Product;
        if($request->categori_id>-1){
            $paginate=Categoria::with('productos')->where('id',$request->categori_id)->get();
            $paginated = CollectionHelper::paginate($paginate[0]->productos, $pag);
            $paginated->appends(['per_page' => $pag,'categori_id'=>$request->categori_id]);
            return $this->showArray($paginated);
        }else{
            $paginate = new Product;
        }

        $pagi=$paginate->orderBy($id,'desc')->paginate($pag);
        

        $pagi->appends(['per_page' => $pag,'categori_id'=>$request->categori_id]);




        return response()->json($pagi,200);

        return $this->paginateall(new Product ,$request); 
    }


    public function store(ProducStore $request){

        $input=$request->inputs();
        $product=Product::create($input);
        
        $categories= json_decode($request->categories);

        if(is_array($categories)){
            foreach ($categories as $category) {
                
                $category= Categoria::find($category->id);
                if($category) ProductsCategoria::create([
                    "product_id"=>$product->id,
                    "categoria_id"=>$category->id,
                ]);
            }
        }
            
        //return response()->json(['error'=>$request->all()],402);
    	return $this->showOne($product);
    }

    public function show(Product $product)
    {
        //
        return $this->showOne($product);
    }

        public function update(ProducUpdate $request, Product $product)
    {
        //
        $input=$request->inputs($product);
        //dd($input);
        $product->fill($input);
        if($product->isClean()){
            return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
        }
        $product->save();
        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\paquete  $paquete
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paquete $paquete)
    {
        //
    }
}
