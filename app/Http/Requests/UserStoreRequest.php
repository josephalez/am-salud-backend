<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserStoreRequest extends FormRequest
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
            "by_user" => 'nullable|exists:users,id',
            'name' => 'required|string|min:4|max:64',
            'username'=>'nullable|min:5|max:64|unique:users',
            'birthday'=> 'required|date',
            'address'=> "nullable|string|max:128",
            'last_name' => 'required|string|min:4|max:64',
            'phone' => 'required|string|max:128',            
            'email' => 'required|string|email|max:128|unique:users',
            'gender' => 'required|in:male,female',
            'password' => 'required|string|min:6|max:64|confirmed',
        ];
    }

    protected function failedValidation(Validator $validator) {

        throw new HttpResponseException(response()->json($validator->errors(), 422));

    }
}
