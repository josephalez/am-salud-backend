<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerEditRequest extends FormRequest
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
            'name' => 'required|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'phone' => 'nullable|string|max:128',
            'instagram' => 'nullable|string|max:64',
            'description' => 'nullable|string',
            'gender' => 'required|in:male,female',
        ];
    }
}

