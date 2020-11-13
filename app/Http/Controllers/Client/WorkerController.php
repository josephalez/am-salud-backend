<?php

namespace App\Http\Controllers\Client;

use App\Models\Worker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\WorkerStoreRequest;

class WorkerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

 /*       $pag=($request->per_page) ? $request->per_page:15;

        $id='id';


        $pagi=Worker::with('service:id,name')->paginate($pag);
        

        $pagi->appends(['per_page' => $pag,'hola'=>'uno']);


        $pagi->withQueryString()->links();

        return response()->json($pagi,200);*/

        $result= Worker::with(['prices']);

        return $this->paginateWithQuery($result ,$request); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
        //
        dd($worker);
        //return $this->showOne($worker);
    }

    public function store(WorkerStoreRequest $request){
        $validated = $request->validated();        

        $data=$request->all();

        $data['password'] = Hash::make($request->get('password'));        

        $user = User::create($data);

        if(!$user) response()->json(["error"=>"Error en la base de datos"],500);

        $user->role="worker";
        $user->area=$data["area"];

        if(!$user->save()) response()->json(["error"=>"Error en la base de datos"],500);        

        return response()->json(["message"=>"miembro del personal añadido"],201);
    }


}
