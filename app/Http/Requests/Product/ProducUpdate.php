<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProducUpdate extends FormRequest
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
            "main_picture"=>'image|mimes:jpeg,bmp,png',
            "picture_uno"=>'image|mimes:jpeg,bmp,png',
            "picture_dos"=>'image|mimes:jpeg,bmp,png',
            //"name"=>'required',
            //"description"=>'required',
            //"stock"=>'',
            //"price"=>'required'
        ];
    }

    public function inputs(Product $product){

        $data=$this->only((new Product())->inputs() );

        if($this->has("main_picture")){
            $data["main_picture"]=$this->main_picture->store('public/products/');
        }
        if($this->has('picture_uno')){
            $data["picture_uno"]=$this->picture_uno->store('public/products/');
        }
        if($this->has("picture_dos")){
            $data["picture_dos"]=$this->picture_dos->store('public/products/');
        }
        if($this->has("stock")){
            $data["stock"]= (int) $product->stock +  ( int ) $data["stock"];
        }
        return $data;
    }
}
