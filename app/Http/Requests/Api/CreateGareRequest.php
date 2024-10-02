<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateGareRequest extends FormRequest
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
            'ville' => 'required|string|max:255',
            "compagnie_id"=>"exists:compagnies,id",
            'commune' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'gare' => 'required|string|max:255|unique:gares,nom',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string|max:20|unique:users,telephone',
            'nomFinancing' => 'nullable|string|max:255',
            'prenomFinancing' => 'nullable|string|max:255',
            'passwordFinancing' => 'nullable|string|min:8',
            'telephoneFinancing' => 'nullable|string|max:20|unique:users,telephone',
            'passwordConfirmation' => 'nullable|string|same:password',
            'passwordConfirmationFinancing' => 'nullable|string|same:passwordFinancing',
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
            'ville.required' => 'La ville est requise.',
            'commune.required' => 'La commune est requise.',
            'nom.required' => 'Le nom est requis.',
            'prenom.required' => 'Le prénom est requis.',
            'gare.required' => 'Le nom de la gare est requis.',
            'gare.unique' => 'Le nom de la gare a déjà été enregistré.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'telephone.unique' => 'Le numéro de téléphone est déjà utilisé.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'nomFinancing.max' => 'Le nom de financement ne peut pas dépasser 255 caractères.',
            'prenomFinancing.max' => 'Le prénom de financement ne peut pas dépasser 255 caractères.',
            'passwordFinancing.min' => 'Le mot de passe de financement doit contenir au moins 8 caractères.',
            'passwordFinancing.confirmed' => 'Les mots de passe de financement ne correspondent pas.',
            'telephoneFinancing.unique' => 'Le numéro de téléphone de financement est déjà utilisé.',
            'passwordConfirmation.same' => 'La confirmation du mot de passe ne correspond pas au mot de passe.',
            'passwordConfirmationFinancing.same' => 'La confirmation du mot de passe de financement ne correspond pas au mot de passe de financement.',
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
        ], 422));
    }
}
