<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Stripe\Exception\InvalidRequestException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Stripe\Exception\AuthenticationException as StripeExecption;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $error=$this->ApiException($request,$exception);
        Log::error($error);
        return $error;
    }

    private function consulta($sql,$bindings){

        foreach($bindings as $key=>$val){
            $bindings[$key]="'".$val."'";
        }

        return Str::replaceArray('?', $bindings, $sql).')';
    }

    protected function ApiException($request, Throwable $exception){
        if($exception instanceof Carbon\Exceptions\InvalidFormatException){
            return $this->errorResponse("nose",500);
            $data=[
            //"HttpStatus"=>$e->getHttpStatus(),
            "JsonBody"=>$exception->getJsonBody(),
            "Message"=>$exception->getMessage(),
            //"Error"=>$e->getError(),
            "user_Info"=>(Auth::check())?Auth::user():null
        ];
            Log::error($data);

        }


        if($exception instanceof ModelNotFoundException ){
            $modelo =strtolower(class_basename( $exception->getModel()));
            return $this->errorResponse("No exite ninguna instancia de {$modelo} con el id expecificado",404);
        }
        //dd($exception);
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la url expecificada ',404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('El metodo expecificado en la peticion no es valido ',405);
        }
        if($exception instanceof QueryException){
            $codigo=$exception->errorInfo[1];
            if($codigo==1451){
                return $this->errorResponse('no se puede eliminar de forma permanente el recurso porque esta relaiconado con algun otro.',409);
            }
            if(config('app.debug')){
                $response=[
                    "info"=>$exception->errorInfo,
                    "sql"=>$exception->getSql(),
                    "bindings"=>$exception->getBindings(),
                    "mensaje"=>$exception->getMessage(),
                    "consulta"=>$this->consulta($exception->getSql(),$exception->getBindings())
                ];
                return $this->errorResponse($response,409);
            }
        }

        if($exception instanceof GuzzleHttp\Exception\ClientException){
           
        }
        if($exception instanceof ValidationException){
            return $this->invalidJson($request,$exception);
        }
        if($exception instanceof StripeExecption || $exception instanceof InvalidRequestException || $exception instanceof CardException){

            return $this->stripeExecption($request,$exception);
        }
        if($exception instanceof AuthorizationException ){
            return $this->errorResponse("esta accion no esta autorizada",403);
        }
        if($exception instanceof HttpException  ){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }
        if(config('app.debug')=="true"){
            //return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Falla inesperada. intente luego ',500);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {


        return  $this->errorResponse(__("auth.not_fund"), 401);
        return $request->expectsJson()
                    ? $this->errorResponse(['message' => "Usuario no autoriazado"], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        //dd($request->path());
        $error= $exception->errors();
        $error['message']='Los siguentes dados no eran válidos.';

        return $this->errorResponse($error, $exception->status);
    }

    private function stripeExecption($re,$e){

                //dd($e);
        $aste="***************";
        $aste.=$aste.$aste;
        Log::channel('stripe')->info($aste.' Fecha '.now()." ".$aste);
        //dd(Auth::check());
        $data=[
            //"HttpStatus"=>$e->getHttpStatus(),
            "JsonBody"=>$e->getJsonBody(),
            "Message"=>$e->getMessage(),
            //"Error"=>$e->getError(),
            "user_Info"=>(Auth::check())?Auth::user():null
        ];
        Log::channel('stripe')->info($data);
        log::Channel('stripe')->info($aste." Fin ".$aste);
        return $this->errorResponse($data,500);
    }

}
