<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Asociado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Asociados\AsociadoStore;

class AsociadoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $asociado=Auth::user()->asociados;

        foreach ($asociado as $key => $value) {
            # code...
            $value->load([
                "reservations"=>function($query){
                    $query->select(DB::raw('count(*) as count,asociado'))->where("servicio_id",2)->groupBy('asociado','servicio_id');
                }
            ]);
        }
        return $this->showArray($asociado);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AsociadoStore $request)
    {
        //
        $data=$request->only([
        'email',
        'name',
        'birthday',
        'genero']);
        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');        
        $data['user_id']=$user=Auth::user()->id;
        $asociado=Asociado::create($data);
        return $this->showOne($asociado);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function show(Asociado $asociado)
    {
        //
        return $this->showOne($asociado);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asociado $asociado)
    {
        //
        $input=$request->only(['email','name','birthday','genero']);

        //return $this->showArray($input);

        $asociado->fill($input);

        if($asociado->isClean()){
            return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
        }
        $asociado->save();
        return $this->showOne($asociado);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asociado $asociado)
    {
        //
        $asociado->delete();
        return $this->showArray([
            'msj'=>'asociado borrado'
        ]);
    }
}
