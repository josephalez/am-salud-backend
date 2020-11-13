<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Controllers\ApiController;
use Auth;
use Illuminate\Support\Facades\DB;

class AssistanceController extends ApiController
{
    public function index($service, Request $request){

        $filter=function ($query) use($service){
            $query->where('status','pagada')
            ->whereHas('myworker', function($q) use($service){
                $q->where('area', $service);
            });
        };
 
        $result= Client::whereHas('reservations', $filter)->with(['reservations'=>$filter, 'reservations.zonas'=>function($q){
            $q->join('zona_lasers', "zona_lasers.id", "zone")
            ->select('*',DB::raw('zones_reservations.id as idZonaReserva'));
        }]); 

        return $this->paginateWithQuery( $result, $request);
    }

    public function mine(Request $request){

        $filter=function ($query) {
            $query->where('status','pagada')
            ->whereHas('myworker', function($q) {
                $q->where('id', Auth::user()->id);
            });
        };

        $result= Client::whereHas('reservations', $filter)->with(['reservations'=>$filter, 'reservations.zonas'=>function($q){
            $q->join('zona_lasers', "zona_lasers.id", "zone")
            ->select('*',DB::raw('zones_reservations.id as idZonaReserva'));
        }]);

        return $this->paginateWithQuery( $result, $request);
    }
}
