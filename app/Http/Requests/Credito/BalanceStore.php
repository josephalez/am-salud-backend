<?php

namespace App\Http\Requests\Credito;


use App\Models\Credito;
use Illuminate\Foundation\Http\FormRequest;

class BalanceStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = request();
        return [
            'credit_id'=>[
                'required',
                function ($attribute, $value, $fail) use($request) {
                    $user=$request->user;
                    if($user->balances()->where('credit_id',$value)->get()->first()){
                        $fail("Ya se agrego este tipo de credito al usuairo");
                    }else{
                        Credito::findOrFail($value);
                    }
                }
            ]
        ];
    }
}
