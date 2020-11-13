<?php

namespace App\Http\Requests\Credito;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoEdit extends FormRequest
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
        return [
            //
            'monto'=>[
                function($attribute,$value,$fail){
                    
                    if(!is_numeric($value)){
                        $fail("El monto debe ser numerico");
                    }
                }
            ]
        ];
    }
}
