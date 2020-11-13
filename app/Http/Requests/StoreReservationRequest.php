<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreReservationRequest extends FormRequest
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
            "reservation_start"=>"required|date_format:Y-m-d H:i|after:12 hours",
            "reservation_end"=>"required|date_format:Y-m-d H:i|after:reservation_start",
            "monto"=>"required",
            //"card_id"=>"required",
            "sun"=>"nullable|boolean",
            "medical"=>"nullable|boolean",
            "radiation"=>"nullable|boolean",
            "sensible_skin"=>"nullable|boolean",
            "hormonal"=>"nullable|boolean",
            "external_product"=>"nullable|boolean",
            "menstruation"=>"nullable|boolean",
            "date"=>"nullable|date",
        ];
    }

    protected function failedValidation(Validator $validator) {

        throw new HttpResponseException(response()->json($validator->errors(), 422));

    }
}
