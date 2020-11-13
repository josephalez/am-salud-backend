<?php

namespace App\Http\Controllers\Client;

use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\MovimientoCredito;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Client\StripeBalanceRequest;

class StripeBalanceController extends ApiController
{
    public function __construct(){
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StripeBalanceRequest $request)
    {

        $user=Auth::user();
        $client=Client::find($user->id);
        $stripeClient=$client->stripe;


        $balance=$client->load(['balances' => function ($query) use($request) {
            $query->where('credit_id', $request->credit_id )->first();
        }])->balances->first();

        if(is_null($balance)){
            $balance=$client->balances()->create($request->only(['credit_id']));
        }
        $cargo=$charge = Charge::create([
          'amount' => $request->amount,
          'currency' => 'usd',
          'description' => 'Cargo por compra de saldo virtual ',
          'customer' => $stripeClient->stripe_id
        ]);

        
        $input=["payment_id"=>4,'monto'=>$request->amount,'pago_stripe_id'=>$cargo->id];
        $movi=$balance->movimientos()->create($input);
        return $this->showArray($movi);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
