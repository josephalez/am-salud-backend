<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZonaLaserEdit extends FormRequest
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
            'name' => 'string|min:4|max:64',
            'completo'=>'required|numeric|min:1',
            'retoque'=>'required|numeric|min:1',
            'time_completo'=>'date_format:i:00',
            'time_retoque'=>'date_format:i:00'
        ];
        
    }
}
