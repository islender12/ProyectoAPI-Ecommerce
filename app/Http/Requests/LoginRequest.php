<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{

    /**
     * Este método se llama cuando la validación falla.
     * En este caso, estamos devolviendo una respuesta JSON con un código de estado 422
     *  y los errores de validación.
     *  De esta manera, puedes mostrar los errores de validación en el cliente que consume la API.
     */

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }

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
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El email es Requerido',
            'email.email' => 'Ingresa un formato de email valido',
            'password.required' => "La contraseña es Requerida",
        ];
    }
}
