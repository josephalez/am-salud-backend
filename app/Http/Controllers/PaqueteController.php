<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Paquetes\PaqueteStore;

class PaqueteController extends ApiController
{
    public function view_saldo(){
        $user=Auth::user()->id;

        


        
        $saldo = DB::select('SELECT saldo , credit_id FROM `moneda_virtual` where user_id=?', [$user]);

        return $this->showArray($saldo);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //


        if($request->has('data_array')){
            return $this->showArray(Paquete::with(['moneda'=> function ($query) use ($request) {
                $query->select(['id','name']);
            }])->where('credit_id',$request->data_array)->get());
        }

        $pag=($request->per_page) ? $request->per_page:15;

        $id='id';


        $pagi=Paquete::with('moneda:id,name')->paginate($pag);
        

        $pagi->appends(['per_page' => $pag,'hola'=>'uno']);


        $pagi->withQueryString()->links();

        return response()->json($pagi,200);



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaqueteStore $request)
    {
        //
        $input=$request->only([     
            'credit_id',
            'nombre',
            'points',
            'status',
            'bonus'
        ]);
        $paquete=Paquete::create($input);



/*         [
          "name" => "Tacos",
          "unit_price" => 1000,
          "quantity" => 120
        ]*/
        
        return $this->showArray($paquete);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\paquete  $paquete
     * @return \Illuminate\Http\Response
     */
    public function show(Paquete $paquete)
    {
        //
        return $this->showOne($paquete);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\paquete  $paquete
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paquete $paquete)
    {
        //

        $input=$request->only(['nombre','monto','status']);

        //return $this->showArray($input);

        $paquete->fill($input);

        if($paquete->isClean()){
            return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
        }
        $paquete->save();
        return $this->showOne($paquete);

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
