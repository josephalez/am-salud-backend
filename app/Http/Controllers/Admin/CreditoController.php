<?php

namespace App\Http\Controllers\Admin;

use App\Models\Credito;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Credito\CreditoEdit;
use App\Http\Requests\Credito\CreditoStore;

class CreditoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->showArray(Credito::All());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreditoStore $request)
    {
        //
        $credito=Credito::create($request->only(['name','description']));
        return $this->showOne($credito);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function show(Credito $credito)
    {
        //
        return $this->showOne($credito);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function update(CreditoEdit $request,Credito $credito )
    {
        $input=$request->only(['name' ,'description']);
        $credito->fill($input);
        if($credito->isClean()){
            return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
        }
        $credito->save();
        return $this->showOne($credito);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credito $credito)
    {
        //
        $credito->delete();
        return $this->showOne($credito);
    }
}
