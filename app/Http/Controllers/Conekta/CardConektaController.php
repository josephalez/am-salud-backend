<?php

namespace App\Http\Controllers\Conekta;

use App\Models\ConektaCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Conekta\ConektaCardStore;

class CardConektaController extends ApiController
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user=Auth::user();
        $card=$user->conekta->cards;
        return $this->showArray($card);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConektaCardStore $request)
    {
        $user=Auth::user();
        //dd($request->all());
        $source=$user->createCard($request->token_card_laser,$request->token_card_nutry);
        $inputs=$request->inputs();
        $inputs["token_src_laser"]=$source["sourceLaser"];
        $inputs["token_src_nutry"]=$source["sourceNutry"];

        $card=$user->conekta->cards()->save(
            new ConektaCard($inputs)
        );




        return $this->showArray($user);

    }
}
