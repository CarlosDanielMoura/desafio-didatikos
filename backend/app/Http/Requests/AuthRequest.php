<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            "email" => [
                "required",
                "email",
            ],
            "password" => [
                "required",
                "string",
                "min:3",
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
            "email.required" => "Email obrigatório",
            "email.email" => "Email inválido",
            "password.required" => "Password obrigatório",
            "password.string" => "Password inválido",
            "password.min" => "Password deve ter no mínimo 3 caracteres",
        ];
    }
}
