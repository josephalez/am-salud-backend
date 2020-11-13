<?php

namespace App\Http\Requests\Asociados;

use App\Models\Asociado;
use Illuminate\Foundation\Http\FormRequest;

class AsociadoStore extends FormRequest
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
            'email'=>'required|string|email|max:128',
            'name'=>'required',
            'birthday'=>'required|date',
            'genero'=>'required|in:'.implode(",", Asociado::genero)
        ];
    }

    public function messages()
    {
        return [
            'genero.in' => 'Los genero validos son '.implode(" o ", Asociado::genero)
        ];
    }
}
