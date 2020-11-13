     <div style="text-align: center;">
          <img src="https://www.amsalud.com/wp-content/uploads/2018/08/logo.png">

          <h1>
               HEALTHY CORNER
          </h1>

          <h1>
               PEDIDO EN PROCESO
          </h1>

          <P>
               hola {{$user->name}} {{$user->last_name}}
          </P>

          <P> Tu pedido numero ({{$pedido->pedido_code}}) esta en proceso</P>

          <p>
              llegara a tu domicilio en un tiempo de 2 a 5 dias habiles apartir de este momento en que lo concretaste 
         </p>

         <table style="margin: auto;">



          @foreach ($pedido->detalle as $detalle)

          <tr>
               <td>
                <img src="{{$detalle->producto->main_picture}}" height="80px" width="80px"> 
           </td>
           <td>
               {{$detalle->producto->name}}<br>
               {{$detalle->cantidad}} X {{$detalle->price}}
          </td>

     </tr>
     @endforeach
</table>

</div>