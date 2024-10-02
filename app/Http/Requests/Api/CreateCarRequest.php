<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCarRequest extends FormRequest
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
            'cars' => 'required|array',
            'cars.*.place' => 'required|integer|max:99',
            'cars.*.imatriculation' => 'required|string|max:20',
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
            'cars.required' => 'La liste des voitures est obligatoire.',
            'cars.array' => 'Les données doivent être un tableau.',
            'cars.*.place.required' => 'Le champ place est obligatoire.',
            'cars.*.place.integer' => 'Le champ place doit être un entier.',
            'cars.*.place.max' => 'Le champ place ne peut pas dépasser 99.',
            'cars.*.imatriculation.required' => 'Le champ imatriculation est obligatoire.',
            'cars.*.imatriculation.string' => 'Le champ imatriculation doit être une chaîne de caractères.',
            'cars.*.imatriculation.max' => 'Le champ imatriculation ne peut pas dépasser 20 caractères.',
        ];
    }
    /**
     * Intercepte les erreurs de validation et renvoie une réponse JSON.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'errors' => $validator->errors(),
            'message' => 'Erreur de validation',
        ], 422));
    }
}
