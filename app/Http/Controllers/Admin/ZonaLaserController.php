<?php

namespace App\Http\Controllers\Admin;

use App\Models\ZonaLaser;
use Illuminate\Http\Request;
use App\Http\Requests\ZonaLaserEdit;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ZonaLaser as FormZonalasers;

class ZonaLaserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        return $this->paginateall(new ZonaLaser,$request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormZonalasers $request)
    {
        //
        $request->validated();

        $input=$request->only(['name' ,'completo','retoque','time_completo','time_retoque']);
        $zona=ZonaLaser::create($input);

        return $this->showOne($zona);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ZonaLaser  $zonaLaser
     * @return \Illuminate\Http\Response
     */
    public function show(ZonaLaser $zonaLaser)
    {
        //
        return $this->showOne($zonaLaser);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ZonaLaser  $zonaLaser
     * @return \Illuminate\Http\Response
     */
    public function update(FormZonalasers $request, ZonaLaser $zonaLaser)
    {
        //
        $request->validated();
        $input=$request->only(['name' ,'completo','retoque','time_completo','time_retoque']);

        $zonaLaser->fill($input);

        if($zonaLaser->isClean()){
            return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
        }
        $zonaLaser->save();
        return $this->showOne($zonaLaser);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZonaLaser  $zonaLaser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZonaLaser $zonaLaser)
    {
        //
        $zonaLaser->delete();

        return $this->showArray(['mesager'=>'Zona borrada correctamente','info'=>$zonaLaser]);
    }
}
