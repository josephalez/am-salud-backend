<?php

namespace App\Http\Requests\Credito;

use App\User;
use App\Models\TypePayments;
use Illuminate\Foundation\Http\FormRequest;

class MovimientoStore extends FormRequest
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
            //
            'payment_id'=>[
                'required',
                function ($attribute, $value, $fail) {
                    TypePayments::findOrFail($value);
                }
            ],
            'worker'=>[
                function ($attribute, $value, $fail) use($request) {
                    $user=User::findOrFail($value);
                    if($user->role==="user"){
                        $fail("este id no tiene permiso de worker");
                    }
                    
                }
            ],
            'monto'=>[
                'required',
                function($attribute,$value,$fail){
                    if(!is_numeric($value)){
                        $fail("El monto debe ser numerico");
                    }
                }
            ]
        ];
    }
}
