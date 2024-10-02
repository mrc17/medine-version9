<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserCompagnieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autorise cette requête par défaut
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
            "nom_compagnie" => ["required", "string", "max:50", "min:4", "unique:compagnies,nom"],
            "sig" => ["required", "string", "max:10", "min:1", "unique:compagnies,sig"],
            "localite" => ["required", "string", "max:255"],
            "telephone_compagnie" => ["required", "string", "max:10", "min:8", "unique:compagnies,contact"],
            "image" => ["required", "string"], // Représente l'image en base64
            "nom_responsable" => ["required", "string", "max:50", "min:2"],
            "prenom_responsable" => ["required", "string", "max:50", "min:2"],
            "telephone_responsable" => ["required", "string", "max:10", "min:8", "unique:users,telephone"],
            "password" => ["required", "string", "max:50", "min:8"],
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
            'nom_compagnie.required' => 'Le nom de la compagnie est requis.',
            'nom_compagnie.unique' => 'Ce nom de compagnie est déjà utilisé.',
            'sig.required' => 'Le sigle est requis.',
            'sig.unique' => 'Ce sigle est déjà utilisé.',
            'localite.required' => 'La localité est requise.',
            'telephone_compagnie.required' => 'Le numéro de téléphone de la compagnie est requis.',
            'telephone_compagnie.unique' => 'Ce numéro de téléphone de la compagnie est déjà utilisé.',
            'image.required' => 'L\'image est requise.',
            'nom_responsable.required' => 'Le nom du responsable est requis.',
            'prenom_responsable.required' => 'Le prénom du responsable est requis.',
            'telephone_responsable.required' => 'Le numéro de téléphone du responsable est requis.',
            'telephone_responsable.unique' => 'Ce numéro de téléphone du responsable est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
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
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Erreur de validation',
            'errors' => $validator->errors()
        ]));
    }
}
