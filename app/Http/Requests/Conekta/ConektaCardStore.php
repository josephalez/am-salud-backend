<?php

namespace App\Http\Requests\Conekta;

use App\Models\ConektaCard;
use Illuminate\Foundation\Http\FormRequest;

class ConektaCardStore extends FormRequest
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
        ];
    }


    public function inputs(){

        $data=$this->only((new ConektaCard())->inputs() );
        return $data;
    }
}
