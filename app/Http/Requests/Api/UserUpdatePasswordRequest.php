<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatePasswordRequest extends FormRequest
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
            'telephone_id' => ['required', 'integer', 'exists:telephones,id'],
            'code' => ['required', 'integer', 'digits:6'], // Correction pour 6 chiffres
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'telephone_id.required' => 'L\'identifiant du téléphone est obligatoire.',
            'telephone_id.integer' => 'L\'identifiant du téléphone doit être un nombre entier.',
            'telephone_id.exists' => 'L\'identifiant du téléphone est invalide.',
            'code.required' => 'Le code est obligatoire.',
            'code.integer' => 'Le code doit être un nombre entier.',
            'code.digits' => 'Le code doit comporter exactement 6 chiffres.', // Correction du message
            'password.required' => 'Le mot de passe est requis.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
