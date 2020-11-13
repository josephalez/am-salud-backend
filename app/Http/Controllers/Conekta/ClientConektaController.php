<?php

namespace App\Http\Controllers\Conekta;

use App\Traits\ConektaTrait;
use Illuminate\Http\Request;
use App\Models\ConektaClient;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class ClientConektaController extends ApiController
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user=Auth::user();
        return $this->showArray($user->showOrCreateClient());
    }
}
