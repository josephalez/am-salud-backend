<?php

namespace App\Http\Controllers\Client;

use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Client;
use App\Models\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class StripeClientController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user=Auth::user();
        $client=Client::find($user->id);

        $cc=$client->stripe;
        return $this->showArray($cc);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Stripe::setApiKey(config('services.stripe.secret'));
        $user=Auth::user();

        $client=Client::find($user->id);
        //dd($user->id);
        $cc=$client->stripe;
        if($cc){
            return $this->showArray($cc);
        }
        $customer=Customer::create([
          'description' => 'Cliente para el usuiaro '.$user->email,
          'name'=>$user->name." ".$user->last_name,
          'email'=>$user->email
        ]);
        $data=[
            'stripe_id'=>$customer->id,
            'user_id'=>$user->id,
            'object'=>$customer->object
        ];
        $stripe=StripeClient::create($data);
        return $this->showArray($stripe);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(StripeClient $stripe)
    {
        //
        Stripe::setApiKey(config('services.stripe.secret'));
        $customer=Customer::retrieve($stripe->stripe_id);
        return $this->showArray($customer);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StripeClient $stripe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(StripeClient $stripe)
    {
        //
    }
}
