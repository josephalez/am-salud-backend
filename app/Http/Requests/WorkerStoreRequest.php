<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class WorkerStoreRequest extends FormRequest
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
            //'username'=>'required|min:5|max:64|unique:users',
            'name' => 'required|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'phone' => 'nullable|string|max:128',
            'instagram' => 'nullable|string|max:64',
            'description' => 'nullable|string',
            'email' => 'required|string|email|max:128|unique:users',
            'gender' => 'required|in:male,female',
            'password' => 'required|string|min:6|max:64|confirmed',
            'area'=> 'required|exists:services,id',
        ];
    }

/*    protected function failedValidation(Validator $validator) {
        if(Request::ajax()) throw new HttpResponseException(response()->json($validator->errors(), 422));
        throw new HttpResponseException(redirect()->back()->withErrors($validator->errors()));

    }*/
}
