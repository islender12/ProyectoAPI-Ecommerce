<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'El Nombre es Requerido',
            'email.required' => 'El email es Requerido',
            'email.email' => 'Ingresa un formato de email valido',
            'password.required' => "La contraseña es Requerida",
            'password.min' => "Ingresa una Contraseña Mayor a 5 Caracteres"
        ];
    }
}
