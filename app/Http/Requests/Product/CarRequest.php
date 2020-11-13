<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'cantidad'=>[
                'required',
                function ($attribute, $value, $fail) use ($request){
                    
                    if($request->has("product_id")){
                        $prod=Product::findOrFail($request->product_id);
                        
                        if($prod->stock<$value){
                            $fail("no tenemos stock suficientes");
                        }
                    }
                    
                }
            ],
            'product_id'=>[
                'required',
                function ($attribute, $value, $fail)  {
                    Product::findOrFail($value);
                }
            ],

        ];
    }
}
