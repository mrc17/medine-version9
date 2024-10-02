<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PayerTicketCaisseRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'trajet_id' => 'required|integer|exists:trajets,id',
            'nbrplace' => 'required|integer|min:1',
            'heureSelect' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'nomOperateur' => ['required', 'string', 'in:Wave,Cash, Moov Money,Mtn Money,Orange Money'],
        ];
    }

    /**
     * Get custom error messages for validation errors.
     */
    public function messages()
    {
        return [
            'user_id.required' => 'L\'ID de l\'utilisateur est obligatoire.',
            'user_id.integer' => 'L\'ID de l\'utilisateur doit être un entier.',
            'user_id.exists' => 'L\'ID de l\'utilisateur est invalide.',
            'trajet_id.required' => 'L\'ID du trajet est obligatoire.',
            'trajet_id.integer' => 'L\'ID du trajet doit être un entier.',
            'trajet_id.exists' => 'L\'ID du trajet est invalide.',
            'nbrplace.required' => 'Le nombre de places est obligatoire.',
            'nbrplace.integer' => 'Le nombre de places doit être un entier.',
            'nbrplace.min' => 'Il doit y avoir au moins une place.',
            'heureSelect.required' => 'L\'heure de sélection est obligatoire.',
            'heureSelect.date_format' => 'L\'heure sélectionnée doit être au format HH:mm:ss.',
            'date.required' => 'La date est obligatoire.',
            'date.date_format' => 'La date doit être au format JJ/MM/AAAA à HH:mm:ss.',
            'nomOperateur.required' => 'Le nom de l\'opérateur est obligatoire.',
            'nomOperateur.in' => 'Le nom de l\'opérateur doit être l\'un des suivants: Wave, Moov Money, Mtn Money, Orange Money.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'errors' => true,
            'message' => 'Erreur de validation',
            'errorsListe' => $validator->errors()
        ], 422));
    }
}
