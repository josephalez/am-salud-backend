<?php

namespace App\Http\Controllers\Client;

use App\Models\ZonaLaser;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ZonaLaserClientController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->paginateall(new ZonaLaser,$request);
    }
    
}
