<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;


class ProducStore extends FormRequest
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
            "main_picture"=>'required|image|mimes:jpeg,bmp,png',
            "picture_uno"=>'image|mimes:jpeg,bmp,png',
            "picture_dos"=>'image|mimes:jpeg,bmp,png',
            "name"=>'required|string|max:64',
            "description"=>'required|string',
            "stock"=>'required|numeric',
            "price"=>'required|numeric', 
            "categories"=>"required|json",
            "categories.*.id"=>'required|exists:categories,id',
        ];
    }

    public function inputs(){

        $data=$this->only((new Product())->inputs() );
        $data["main_picture"]=$this->main_picture->store('public/products/');

        if($this->has('picture_uno')){
            $data["picture_uno"]=$this->picture_uno->store('public/products/');
        }
        if($this->has("picture_dos")){
            $data["picture_dos"]=$this->picture_dos->store('public/products/');
        }
        $data['category']=$this->category;
        return $data;
    }
}
