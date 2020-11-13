<?php

namespace App\Http\Controllers;

use App\Hour;
use App\User;
use Exception;
use Carbon\Carbon;
use App\Reservation;
use App\Models\Worker;
use App\Models\MiPaquete;
use App\Models\ZonaLaser;
use App\Models\ZonaReserva;
use Illuminate\Http\Request;
use App\Mail\NotificacionReserva;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreReservationRequest;

use App\Models\LaserForm;

class ReservationsController extends Controller
{
    public function add(StoreReservationRequest $request, Worker $worker){
        $validated= $request->validated();
        $data=$request->all();
        if(Auth::user()->id==$worker->id) return response()->json(["error"=>"No puedes reservar tus propias horas"],400);
        if(!$worker||$worker->role!="worker") return response()->json(['error'=>"Profesional no válido"],400);
        $day=Carbon::createFromFormat('Y-m-d H:i',$data["reservation_start"]);
        $end=Carbon::createFromFormat('Y-m-d H:i',$data["reservation_end"]);
        if(!$day->isSameDay($end)) return response()->json(["error"=>"La cita debe concluir el mismo día"],400);
        $same_reservation= Reservation::where("worker","=",$worker->id)
        ->where("reservation_end", ">", $data['reservation_start'])
        ->where("reservation_start", "<", $data['reservation_end'])
        ->where(function($q) use($data){
            $q->where(function($q) use($data){
                $q->where("reservation_start",">=",$data['reservation_start'])
                ->where("reservation_end", "<=", $data['reservation_end']);
            })
            ->orWhere(function($q) use($data){
                $q->where("reservation_start","=",$data['reservation_start'])
                ->orWhere("reservation_end", "=", $data['reservation_end']);
            })
            ->orWhere(function($q) use($data){
                $q->where("reservation_start", "<", $data['reservation_start'])
                ->where("reservation_end", ">", $data['reservation_end']);
            })
            ->orWhere(function($q) use($data){
                $q->where("reservation_start", "<", $data['reservation_start'])
                ->orWhere("reservation_end", ">", $data['reservation_end']);
            });
        })->first();
        if($same_reservation) return response()->json(["error"=> "Ya hay una reservación que coincide con esta hora",'data'=>$data],400);
        $hour= Hour::where("worker","=",$worker->id)
        ->where('start_hour',">=",$data["reservation_start"])
        ->where('finish_hour',"<=",$data["reservation_end"])
        ->where("day", "=", $day->dayOfWeekIso);
        if(!$hour) return response()->json(["error"=>"Este intérvalo no está disponible"],404);
        $user=Auth::user();
        $por=0;
        if($request->has("por")){
            $por=$request->por*1;
        }
        if($request->typepayment=="3"){
            if($request->has("zonas")){
                $item=[];
                foreach ($request->input("zonas") as $key => $value) {
                    $zona=ZonaLaser::find($value["zone"]);
                    $retoque=($value["is_retoque"]==true) ? "Retoque":"";
                    $cobro=$value["monto_zona"];
                    if($por>0){
                        $cobro=$cobro-($cobro*($por/100));
                    }
                    $item[]=[
                        "name" => "depilacion zona ".$zona->name." ".$retoque,
                        "unit_price" => intval($cobro*100),
                        "quantity" => 1
                    ];
                }
                $orderid=$user->createOrder($item,$request->card_id,"laser");
                $data["pagado"]=1;
            }else{
                $monto=$worker->prices->tarifa;
                $cobro=$monto;
                if($por>0){
                    $cobro=$cobro-($cobro*($por/100));
                }
                $item[]=[
                    "name" => "Cita con el nutricionista  ",
                    "unit_price" => intval($cobro*100),
                    "quantity" => 1
                ];
                $orderid=$user->createOrder($item,$request->card_id,"nutry");
            }
            $data["pago_stripe_id"]= $orderid;
        }
        if($request->typepayment=="2"){
            $user->saldoVirtual($request->monto,User::find($worker->id)->area);
        }
        if($request->has('asociado')){
            $data["asociado"]=$request->asociado;
        }

        $data["user"]=Auth::user()->id;
        $data["worker"]=$worker->id;
        $data["servicio_id"]=User::find($worker->id)->area;

        $data["payment_id"]= $request->typepayment;

        $reservation= Reservation::create($data);

        if($request->has("zonas")){

            $reservation->zonas()->createMany($request->zonas);
        }




    if(!$reservation) return response()->json(["error"=>"Error en la base de datos"],500);

    if($reservation->servicio_id==2){
        $laser_form= new LaserForm;
        $laser_form->reservation=$reservation->id;
        if($request->has('sun')){
            $laser_form->sun=$request->sun;
        }
        if($request->has('medical')){
            $laser_form->medical=$request->medical;
        }
        if($request->has('radiation')){
            $laser_form->radiation=$request->radiation;
        }
        if($request->has('sensible_skin')){
            $laser_form->sensible_skin=$request->sensible_skin;
        }
        if($request->has('hormonal')){
            $laser_form->hormonal=$request->hormonal;
        }
        if($request->has('external_product')){
            $laser_form->external_product=$request->external_product;
        }
        if($request->has('menstruation')){
            $laser_form->menstruation=$request->menstruation;
        }
        if($request->has('date')&&$request->date!=''){
            $laser_form->date=$request->date;
        }
        $laser_form->save();
    }
    
    return response()->json(["message"=>"Reservación agendada"],200);
}

    public function cancel(Reservation $reservation){

        if(!$reservation) return response()->json(['error'=>"No se encontró la reservación"],404);

        if(!$reservation->worker===Auth::user()->id&&Auth::user()->role!='admin') return response()->json(["error"=>"No tienes permisos"],403);

        $reservation->status="cancelada";        

        if(!$reservation->save()) return response()->json(["error"=>"error en la base de datos"],500);

        return response()->json(["message"=>"reserva cancelada"],200);

    }

    public function confirm($reservation_id){

        $reservation=  Reservation::find($reservation_id);

        if(!$reservation) return response()->json(['error'=>"No se encontró la reservación"],404);

        if(!$reservation->worker===Auth::user()->id&&Auth::user()->role!='admin') return response()->json(["error"=>"No tienes permisos"],403);

        if($reservation->status=="cancelada") return response()->json(["error"=>"Esta cita fue cancelada"], 400);

        if($reservation->status=="pagada") return response()->json(["error"=>"Esta cita ya fue pagada"], 400);
 
        $reservation->status="pagada";

        if(!$reservation->save()) return response()->json(["error"=>"error en la base de datos"],500);

        return response()->json(["message"=>"reserva confirmada"],200);

    }
 
    public function myReservations(Request $request){

        $today = Carbon::today();

        if($request->input('from_month')){
            try{
                $today= Carbon::createFromFormat('Y-m-d',$request->input('from_month'));
            }catch(Exception $e){
                return response()->json(["error"=>"la fecha no es válida"],400);
            }
        }

        return DB::table('reservations')
        ->where("worker",Auth::user()->id)
        ->whereBetween('reservation_start', array($today->startOfMonth()->toDateTimeString(), $today->endOfMonth()->toDateTimeString()))
        ->join("users", 'users.id', 'reservations.user')
        ->select(
            "reservations.*",
            'users.profile_picture as user_avatar',
            DB::raw('concat(users.name, " ", users.last_name) as user_name')
        )->get();
    }

    public function mine(){
        return Reservation::where('user', '=' , Auth::user()->id)
        ->orderByDesc('reservation_start')
        ->join('users', 'users.id', 'reservations.worker')
        ->join('services', 'services.id', 'users.area')
        ->select(
            'reservations.*',
            'users.name as worker_name', 
            'services.name as service_name',
        )->with(['asociado'])->get();

    }


    public function cancelClient(Reservation $reservation){
        $reservation->canceled=1;
        $reservation->confirmed=0;
        $reservation->save();
        return response()->json(["message"=>"reserva confirmada"],200);
    }
    public function confirmClient(Reservation $reservation){
        if($reservation->canceled=="0"){
            $reservation->confirmed=1;
            $reservation->save();
            return response()->json(["message"=>"reserva confirmada"],200);
        }else{
            return response()->json(["message"=>"esta reserva fue cancelada"],409);
        }
    }

    public function primeraVez(){
        $user=Auth::user();

        $user->load([
            "reservations"=>function($query){
                $query->select(DB::raw('count(*) as count,user'))->where("servicio_id",2)->where('asociado',null)->groupBy('user','servicio_id');
            }
        ]);
        $res=$user->reservations;

        if(isset($res[0])){
            return response()->json(false);
        }else{
            return response()->json(true);
        } 
    }

    public function devolucion(Request $request,$reserva, $zona){


        $reserva=Reservation::findOrFail($reserva);
        $zona=ZonaReserva::findOrFail($zona);


        if($reserva->canceled!=0){
            return response()->json(["error"=>"Esta reserva esta cancelada"],409);
        }

        if($zona->status!=0){
            return response()->json(["error"=>"Esta zona ya fue devuleta "],409);
        }

        $zona->status=1;
        $zona->save();

        $monto=$zona->monto_zona;

        $paquete=MiPaquete::create([
            'user_id'=>$reserva->user,
            'payment_id'=>1,
            'pago_stripe_id'=>"",
            'saldo'=>$monto,
            'monto'=>$monto,
            'restante'=>0,
            'credit_id'=>$reserva->servicio_id,
            'worker'=>Auth::user()->id
        ]);


        return response()->json([$reserva,$zona]);
    }

}
