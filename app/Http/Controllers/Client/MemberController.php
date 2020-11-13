<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;


class MemberController extends ApiController

{
    public function index($service, Request $request)
    {
        return $this->paginateWithQuery( Worker::where("area", "=", $service) ,$request); 
    }

}
