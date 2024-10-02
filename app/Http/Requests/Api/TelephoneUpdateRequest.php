<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TelephoneUpdateRequest extends FormRequest
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
            'code' => ['required', 'integer'],
            'password' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50', 'min:2'],
            'prenom' => ['required', 'string', 'max:50', 'min:2'],
            'telephone_id' => ['required', 'integer', 'exists:telephones,id'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'telephone_id.required' => 'L\'ID du téléphone est requis.',
            'telephone_id.integer' => 'L\'ID du téléphone doit être un entier.',
            'telephone_id.exists' => 'Le téléphone spécifié n\'existe pas.',
            'code.required' => 'Le code est requis.',
            'code.integer' => 'Le code doit être un entier.',
            'password.required' => 'Le mot de passe est requis.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.max' => 'Le mot de passe ne peut pas dépasser 50 caractères.',
            'nom.required' => 'Le nom est requis.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 50 caractères.',
            'nom.min' => 'Le nom doit comporter au moins 2 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 50 caractères.',
            'prenom.min' => 'Le prénom doit comporter au moins 2 caractères.',
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
