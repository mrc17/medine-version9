<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTrajetRequest extends FormRequest
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
            "depart" => ["required", "max:30", "min:4"],
            "arrivee" => ["required", "max:30", "min:4"],
            "prix" => ["required", "numeric"], // Le prix doit être un nombre
            "mode_depart_id" => ["required", "integer"], // mode_depart_id est aussi un entier (ID)
            "heures" => ['required_if:mode_depart_id,2', 'array'], // Les heures doivent être un tableau si mode_depart_id est 2
        ];
    }

    /**
     * Get custom error messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'depart.required' => 'Le champ départ est obligatoire.',
            'depart.max' => 'Le champ départ ne doit pas dépasser 30 caractères.',
            'depart.min' => 'Le champ départ doit comporter au moins 4 caractères.',
            'arrivee.required' => 'Le champ arrivée est obligatoire.',
            'arrivee.max' => 'Le champ arrivée ne doit pas dépasser 30 caractères.',
            'arrivee.min' => 'Le champ arrivée doit comporter au moins 4 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'mode_depart_id.required' => 'L\'ID du mode de départ est obligatoire.',
            'mode_depart_id.integer' => 'L\'ID du mode de départ doit être un entier.',
            'heures.required_if' => 'Le champ heures est obligatoire lorsque le mode de départ est défini.',
            'heures.array' => 'Le champ heures doit être un tableau valide.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
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
