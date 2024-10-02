<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangeCodeWalletRequest extends FormRequest
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
            'password' => 'required|digits:4', // Code actuel
            'new_password' => 'required|digits:4|confirmed', // Nouveau code
            "wallet_code" => "required|integer" // Code du portefeuille
        ];
    }

    /**
     * Obtenez les messages de validation personnalisés.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Le code de portefeuille actuel est requis.',
            'password.digits' => 'Le code de portefeuille doit comporter exactement 4 chiffres.',
            'new_password.required' => 'Le nouveau code de portefeuille est requis.',
            'new_password.digits' => 'Le nouveau code de portefeuille doit comporter exactement 4 chiffres.',
            'new_password.confirmed' => 'La confirmation du nouveau code de portefeuille ne correspond pas.',
        ];
    }

    /**
     * Intercepts validation errors and returns a JSON response.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors() // Détails des erreurs de validation
        ]));
    }
}
