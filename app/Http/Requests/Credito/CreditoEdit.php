<?php

namespace App\Http\Requests\Credito;

use Illuminate\Foundation\Http\FormRequest;

class CreditoEdit extends FormRequest
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
        $id=0;
        if($request->credito){
            $id=$request->credito->id;
        }

        return [
            'name'=>'unique:creditos,name,'.$id,
        ];
    }
}
