<?php

namespace App\Http\Controllers\Client;

use Stripe\Token;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Client;
use App\Models\StripeCard;
use App\Models\StripeClient;
use Illuminate\Http\Request;
use App\Http\Requests\Client\Card;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class StripeCardController extends ApiController
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
        $user=Auth::user();
        $client=Client::find($user->id);

        $client=$client->stripe;
        $card=$client->card;
        return $this->showArray($card);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Card $request)
    {
        //
        $user=Auth::user();
        $client=Client::find($user->id);
        $client=$client->stripe;
        
        $token=Token::create([
            'card' => [    
                'number' => $request->card_number,
                'exp_month' => $request->month,
                'exp_year' => $request->year,
                'cvc' => $request->cvc,
            ],
        ]);

        $card=Customer::createSource(
            $client->stripe_id,
            ['source'=>$token->id]
        );

        $card2=StripeCard::create([
            "stripe_card_id"=>$card->id,
            "stripe_client_id"=>$client->id,
            "card_number"=>substr($request->card_number,-4)
        ]);
        return $this->showArray($card2);
    }

    /**
     * Display the specified resource. 
     *
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,StripeClient $stripe,StripeCard $card)
    {
        //


        return $this->showArray([$stripe,$card]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StripeCard $stripeCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(StripeCard $stripeCard)
    {
        //
    }
}
