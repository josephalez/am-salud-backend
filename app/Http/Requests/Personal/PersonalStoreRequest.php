<?php

namespace App\Http\Requests\Personal;

use Illuminate\Foundation\Http\FormRequest;

class PersonalStoreRequest extends FormRequest
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
            "name"=>'string|required',
            "office"=>'string|required',
            "description"=>'string|nullable',
            "profile_picture"=>'nullable',
            "instagram"=>'string|nullable|max:32',
        ];
    }
}
