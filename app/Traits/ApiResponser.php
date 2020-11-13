<?php 

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait ApiResponser{
	private function sussessResponse($data,$code){
        return response()->json(["data"=>$data],$code);
    }
    protected function errorResponse($message,$code){
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }
    protected function showAll( Collection $collection ,$code=200, $tranfor=null){
        if($collection->isEmpty()){
            return $this->sussessResponse(['data'=>$collection],$code);
        }
        return $this->sussessResponse($collection,$code);
    }
    protected function showOne(Model $instance ,$code=200){
        return $this->sussessResponse($instance,$code);
    }
    protected function showArray($array,$code=200){
        return $this->sussessResponse($array,$code);
    }

    protected function paginateall( Model $paginate,Request $request,$code=200){

        $pag=($request->per_page) ? $request->per_page:15;

        $id='id';

        $pagi=$paginate::orderBy($id,'desc')->paginate($pag);
        

        $pagi->appends(['per_page' => $pag,'hola'=>'uno']);


        $pagi->withQueryString()->links();
        //print_r($pagi);

        //dd($pagi->{'total'});

        return response()->json($pagi,$code);
    }

    protected function paginateWithQuery($paginate,Request $request,$code=200){

        $pag=($request->per_page) ? $request->per_page:15;

        $id='id';

        $pagi= $paginate->orderBy($id,'desc')->paginate($pag);
        
        $pagi->appends(['per_page' => $pag,'hola'=>'uno']);

        $pagi->withQueryString()->links();

        return response()->json($pagi,$code);
    }

    protected function paginateall2(Collection $paginate,Request $request,$code=200){

        $pag=($request->per_page) ? $request->per_page:15;

        $id='id';


        $pagi=$paginate->sortBy($id,'desc')->paginate($pag);
        

        $pagi->appends(['per_page' => $pag,'hola'=>'uno']);


        $pagi->withQueryString()->links();
        //print_r($pagi);

        //dd($pagi->{'total'});

        return response()->json($pagi,$code);
    }
}