<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'NAME_CITY' => [
                'required',
                'string',
                'max:150',
                'unique:city,NAME_CITY',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'NAME_CITY.required' => 'O campo nome da cidade é obrigatório',
            'NAME_CITY.string' => 'O campo nome da cidade deve ser uma string',
            'NAME_CITY.max' => 'O campo nome da cidade deve ter no máximo 150 caracteres',
            'NAME_CITY.unique' => 'Essa cidade já está cadastrada',
        ];
    }
}
