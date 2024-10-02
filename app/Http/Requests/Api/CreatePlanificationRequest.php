<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreatePlanificationRequest extends FormRequest
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
            "date" => ["required", "date", "date_format:Y-m-d"], // Format de la date valide 'Y-m-d'
            "car_id" => ["required", "exists:cars,id"], // Vérifie que `car_id` existe dans la table `cars`
            "heure" => ["required", "date_format:H:i:s"], // Format de l'heure valide 'HH:MM:SS'
            "planification" => ["required", "array"],
            "planification.*.trajet_id" => ["required", "exists:trajets,id"], // Vérifie que `trajet_id` existe dans la table `trajets`
        ];
    }


    /**
     * Get custom error messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'date.required' => 'La date est obligatoire.',
            'date.date' => 'La date doit être une date valide.',
            'date.date_format' => 'Le format de la date doit être Y-m-d.',
            'car_id.required' => 'L\'ID de la voiture est obligatoire.',
            'car_id.exists' => 'La voiture spécifiée n\'existe pas.',
            'heure.required' => 'L\'heure est obligatoire.',
            'heure.date_format' => 'Le format de l\'heure doit être H:i:s.',
            'planification.required' => 'La planification est obligatoire.',
            'planification.array' => 'La planification doit être un tableau.',
            'planification.*.trajet_id.required' => 'L\'ID du trajet est obligatoire.',
            'planification.*.trajet_id.exists' => 'Le trajet spécifié n\'existe pas.',
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
