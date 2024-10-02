<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCompagnieRequest extends FormRequest
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
            'contact' => 'required|string|max:255',
            'localite' => 'required|string|max:255',
            'nom' => 'required|string|max:255|unique:compagnies,nom',
            'sig' => 'required|string|max:255|unique:compagnies,sig',
        ];
    }

    /**
     * Custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la compagnie est requis.',
            'sig.required' => 'Le sigle de la compagnie est requis.',
            'valide.required' => 'La validation de la compagnie est requise.',
            'contact.required' => 'Le contact de la compagnie est requis.',
            'localite.required' => 'La localité de la compagnie est requise.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'sig.string' => 'Le sigle doit être une chaîne de caractères.',
            'valide.boolean' => 'La validation doit être un booléen.',
            'contact.string' => 'Le contact doit être une chaîne de caractères.',
            'localite.string' => 'La localité doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'sig.max' => 'Le sigle ne peut pas dépasser 255 caractères.',
            'contact.max' => 'Le contact ne peut pas dépasser 255 caractères.',
            'localite.max' => 'La localité ne peut pas dépasser 255 caractères.',
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
}
