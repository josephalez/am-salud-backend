<?php

namespace App\Http\Requests\Client;

use App\Models\Credito;
use Illuminate\Foundation\Http\FormRequest;

class StripeBalanceRequest extends FormRequest
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
            'credit_id'=>[
                'required',
                function ($attribute, $value, $fail) {
                    Credito::findOrFail($value);
                }
            ],
            'amount'=>'required'
        ];
    }
}
