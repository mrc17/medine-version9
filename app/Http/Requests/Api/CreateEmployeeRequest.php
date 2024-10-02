<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }

    /**
     * Obtenez des messages d'erreur personnalisés pour les erreurs de validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 15 caractères.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.max' => 'Le mot de passe ne peut pas dépasser 50 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
        ];
    }

    /**
     * Gérer une tentative de validation échouée.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Vérifie la contrainte d'unicité du numéro de téléphone en fonction du rôle.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $telephone = $this->input('telephone');
            $role_id = $this->input('role_id');

            // Vérifiez la contrainte d'unicité du numéro de téléphone
            $exists = DB::table('users')
                ->where('telephone', $telephone)
                ->where('role_id', $role_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('telephone', 'Ce numéro de téléphone est déjà associé à un utilisateur avec ce rôle.');
            }
        });
    }
}
