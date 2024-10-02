<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autoriser la requête pour tous les utilisateurs
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the current user's phone number
        $user = Auth::user();

        return [
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            // Update the unique rule to exclude the current user's phone number
            'telephone' => [
                'nullable',
                'string',
                'max:20',
                'unique:users,telephone,' . $user->id // Exclude the current user's ID from the unique check
            ],
            'password' => 'sometimes|nullable|string|min:8',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
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
