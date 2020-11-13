<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Auth;

class ExpedientsController extends ApiController
{
    public function index($service, Request $request){
        $filter=function ($query) use($service){
            $query->whereHas('myworker', function($q) use($service){
                $q->where('area', $service);
            });
        };

        $result= Client::whereHas('reservations', $filter)->with(['reservations'=>$filter,'reservations.laserForm',
        'reservations.zonas'=>function($q){
            $q->join('zona_lasers', "zona_lasers.id", "zone")
            ->select('*',DB::raw('zones_reservations.id as idZonaReserva'));
        }]);

        return $this->paginateWithQuery( $result, $request);
    }

    public function mine(Request $request){
        $filter=function ($query) {
            $query->whereHas('myworker', function($q) {
                $q->where('id', Auth::user()->id);
            });
        };

        $result= Client::whereHas('reservations', $filter)->with(['reservations'=>$filter,'reservations.laserForm',
        'reservations.zonas'=>function($q){
            $q->join('zona_lasers', "zona_lasers.id", "zone")
            ->select('*',DB::raw('zones_reservations.id as idZonaReserva'));
        }]);

        return $this->paginateWithQuery( $result, $request);
    }
}
