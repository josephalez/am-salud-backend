<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Models\TypePayments;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PaymentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //$pag=($request->per_page)? $request->per_page;
        //$paginate=TypePayments::paginate($pag);

        return $this->paginateall(new TypePayments ,$request); 
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
        $request->validate([
            'name'=>'required',
            'description'=>'required'
        ]);
        $inpust=$request->only('name','description');
        $typePayment=TypePayments::create($inpust);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TypePayments  $typePayments
     * @return \Illuminate\Http\Response
     */
    public function show(TypePayments $typePayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TypePayments  $typePayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypePayments $typePayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TypePayments  $typePayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypePayments $typePayments)
    {
        //
    }
}
