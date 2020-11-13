<?php

namespace App\Http\Controllers;

use App\Hour;
use App\HourBlock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HourStoreRequest;

class HoursController extends Controller
{

    public function getSchedule($worker){

        $hours=DB::table("hours")
        ->where("worker",$worker)
        ->get();

        $hoursArray=[];

        foreach ($hours as $key => $hour) {
            $hour->hour_blocks=HourBlock::where("hour","=",$hour->id)->get();
            array_push($hoursArray,$hour);
        }

        return $hoursArray;

    }

    public function getMine(){

        return Hour::where("worker","=", Auth::user()->id)
        ->get();

    }

    public function removeDayOff($day){

        if($day>7||$day<1) return response()->json(["error"=>"Ingrese un día de semana válido"],400);

        $hour=Hour::where("worker","=", Auth::user()->id)->where("day","=",$day)->first();

        if(!$hour) return response()->json(["error"=>"No hay horario en este día"]);

        if(!$hour->delete()) return response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(["message"=>"Se ha descartado este día como laborable"]);

    }

    public function addHourRange(HourStoreRequest $request){

        $validated = $request->validated();

        $data=$request->all();

        $user= Auth::user();

        $data["worker"]=$user->id;

        $start=Carbon::createFromFormat('H:i', $data['start_hour']);
        $end=Carbon::createFromFormat('H:i', $data['finish_hour']);

        if($start->diffInMinutes($end)<30){
            return response()->json(["error"=>"El bloque de horario mínimo es de 30 minutos"],400);
        }

        $hour=Hour::fetchData($data);
        if(!$hour) return response()->json(["error"=>"error en la base de datos"],500);
        
        return response()->json([
            "message"=>"Horario actualizado, Se ".($hour>1?'generaron ':'generó ').$hour.($hour>1?" bloques":" bloque")." de hora"
        ],200);

    }

}
