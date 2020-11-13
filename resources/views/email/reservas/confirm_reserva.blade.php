Se necesita la confirmacion para la reserva {{$reserva->reservation_start}}
con el especialista {{$reserva->myworker->name}}

<a href="{{route('confirm.reservations',['reservation'=>$reserva->id ] )}}">Confirma</a>