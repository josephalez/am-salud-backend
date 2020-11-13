<?php

namespace App\Http\Controllers\Admin\Users;

use Illuminate\Http\Request;
use App\Models\BalanceCredito;
use App\Models\MovimientoCredito;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Credito\MovimientoEdit;
use App\Http\Requests\Credito\MovimientoStore;

class BalanceMovimientosController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request ,BalanceCredito $balance)
    {
        //
        return $this->showArray($balance);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovimientoStore $request,BalanceCredito $balance)
    {
        //
        $movimiento=$balance->movimientos()->create($request->only(['payment_id','worker','monto']));
        $balance->saldo=0;
        return $this->showArray($movimiento);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id ,MovimientoCredito $movimiento)
    {
        //
        return $this->showArray($movimiento);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MovimientoEdit $request, BalanceCredito $balance, MovimientoCredito $movimiento)
    {
        //
        if($balance->movimientos()->where('id',$movimiento->id)->get()->first()){
            $input=$request->only(['monto']);
            $movimiento->fill($input);
            if($movimiento->isClean()){
                return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
            }
            $movimiento->save();
            return $this->showOne($movimiento);

        }else{
            return $this->errorResponse("los indices no coincide",409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
