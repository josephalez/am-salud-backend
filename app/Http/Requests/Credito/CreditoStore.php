<?php

namespace App\Http\Requests\Credito;

use Illuminate\Foundation\Http\FormRequest;

class CreditoStore extends FormRequest
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
            'name'=>'required|unique:creditos,name|max:255',
            'description'=>'required'
        ];
    }
}
