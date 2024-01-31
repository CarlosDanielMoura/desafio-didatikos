<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "NAME_PRODUCT" => [
                "required",
                "string",
                "max:255"
            ],
            "PRICE" => [
                "required",
                "numeric",
                "min:0"
            ],
            "BRAND_PRODUCT" => [
                "required",
                "integer",
                "exists:brand,COD_BRAND"
            ],
            "STOCK" => [
                "required",
                "numeric",
                "min:0"
            ],
            "CITY" => [
                "required",
                "integer",
                "exists:city,COD_CITY"
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "NAME_PRODUCT.required" => "O campo nome é obrigatório",
            "NAME_PRODUCT.string" => "O campo nome deve ser uma string",
            "NAME_PRODUCT.max" => "O campo nome deve ter no máximo 255 caracteres",
            "PRICE.required" => "O campo preço é obrigatório",
            "PRICE.numeric" => "O campo preço deve ser um número",
            "PRICE.min" => "O campo preço deve ser maior ou igual a 0",
            "BRAND_PRODUCT.required" => "O campo marca é obrigatório",
            "BRAND_PRODUCT.integer" => "O campo marca deve ser um número inteiro",
            "BRAND_PRODUCT.exists" => "O campo marca deve ser um código de marca válido",
            "STOCK.required" => "O campo estoque é obrigatório",
            "STOCK.numeric" => "O campo estoque deve ser um número",
            "STOCK.min" => "O campo estoque deve ser maior ou igual a 0",
            "CITY.required" => "O campo cidade é obrigatório",
            "CITY.integer" => "O campo cidade deve ser um número inteiro",
            "CITY.exists" => "O campo cidade deve ser um código de cidade válido",
        ];
    }
}
