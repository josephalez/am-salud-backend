<?php

namespace App\Http\Controllers\Admin\Users;

use App\User;
use App\Models\Client;
use App\Models\Credito;
use Illuminate\Http\Request;
use App\Models\BalanceCredito;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Credito\BalanceStore;

class UserBalanceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Client $user)
    {
        //
        return $this->showArray($user->balances);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BalanceStore $request,Client $user)
    {
        $balance = $user->balances()->create($request->only(['credit_id']));
        return $this->showArray($balance);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Client $user,BalanceCredito $balance)
    {
        return $this->showArray($user->balances()->where('credit_id',1)->get()->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Client $user,BalanceCredito $balance)
    {
        //
        if($user->balances()->where('id',$balance->id)->get()->first()){

            return $this->showArray($balance);

            $input=$request->only(['st']);
            $balance->fill($input);
            if($balance->isClean()){
                return $this->errorResponse('Debe Especificar al menos un valor diferente para actualizar',422);
            }
            $balance->save();
            return $this->showOne($balance);

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
