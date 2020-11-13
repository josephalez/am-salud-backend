<?php


namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Exceptions\HttpResponseException;

use App\HourBlock;

class Hour extends Model
{

    protected $fillable=[
        'worker',
        'start_hour',
        'finish_hour',
        'day',
    ];

    public static function fetchData($data){

     

        $hour=Hour::where("worker","=",$data["worker"])->where("day","=",$data["day"])->first();

        if($hour){
            if(!$hour->delete()) return false;
        }

        $hour=Hour::create($data);

        if(!$hour) return false;

        else{
            $start=Carbon::createFromFormat('H:i', $data['start_hour']);
            $end=Carbon::createFromFormat('H:i', $data['finish_hour']);

            $counter=0;

            for($d=$start; $d<$end; $d->addMinutes(30)){

                $start_time=$d->toTimeString();
                $d->addMinutes(30);
                $end_time=$d->toTimeString();
                $d->subMinutes(30);

                $hourBlock=HourBlock::create([
                    "hour"=>$hour->id,
                    "start"=>$start_time,
                    "end"=>$end_time,
                ]);

                if(!$hourBlock) return false;

                $counter++;

            }
        }

        return $counter;

    }

}
