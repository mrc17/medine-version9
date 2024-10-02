<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PayerTicketRequest extends FormRequest
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
            "date" => ["required", "string",],
            "numero" => ["required", "string"],
            "montant" => ["required", "integer"],
            "heureSelect" => ["required", "string"],
            "user_id" => ["required", "exists:users,id"],
            "gareSelection" => ["required", "string", "exists:gares,nom"],
            "compagnie" => ["required", "string", "exists:compagnies,sig"],
            "DepartVille" => ["required", "string", "exists:trajets,depart"],
            "ArriverVille" => ["required", "string", "exists:trajets,arrivee"],
            "nomOperateur" => ["required", "string", "in:Wave,Moov Money,Mtn Money,Orange Money"],
            "opt" => ["required_if:nomOperateur,Orange Money"],
            'nbrTicket' => ['required', 'numeric', function ($attribute, $value, $fail) {
                // Votre règle personnalisée ici
                if ($value > 2) {
                    $fail("Le champ $attribute doit être inférieur à 2.");
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            "ArriverVille.required" => "Le champ ArriverVille est obligatoire.",
            "ArriverVille.string" => "Le champ ArriverVille doit être une chaîne de caractères.",
            "ArriverVille.exists" => "La ville d'arrivée spécifiée n'existe pas dans la liste des trajets.",
            "user_id.exists" => "L'utilisateur sélectionné n'existe pas.",
            "user_id.required" => "L'utilisateur sélectionné n'existe pas.",

            "DepartVille.required" => "Le champ DepartVille est obligatoire.",
            "DepartVille.string" => "Le champ DepartVille doit être une chaîne de caractères.",
            "DepartVille.exists" => "La ville de départ spécifiée n'existe pas dans la liste des trajets.",

            "compagnie.required" => "Le champ compagnie est obligatoire.",
            "compagnie.string" => "Le champ compagnie doit être une chaîne de caractères.",
            "compagnie.exists" => "La compagnie spécifiée n'existe pas dans la liste des compagnies.",

            "date.required" => "Le champ date est obligatoire.",
            "date.string" => "Le champ date doit être une chaîne de caractères.",

            "gareSelection.required" => "Le champ gareSelection est obligatoire.",
            "gareSelection.string" => "Le champ gareSelection doit être une chaîne de caractères.",
            "gareSelection.exists" => "La gare spécifiée n'existe pas dans la liste des gares.",

            "heureSelect.required" => "Le champ heureSelect est obligatoire.",
            "heureSelect.string" => "Le champ heureSelect doit être une chaîne de caractères.",

            "montant.required" => "Le champ montant est obligatoire.",
            "montant.integer" => "Le champ montant doit être un nombre entier.",

            "nomOperateur.required" => "Le champ nomOperateur est obligatoire.",
            "nomOperateur.string" => "Le champ nomOperateur doit être une chaîne de caractères.",
            "nomOperateur.exists" => "L'opérateur spécifié n'existe pas dans la liste des opérateurs.",
            "nomOperateur.in" => "Le champ nomOperateur doit être l'un des opérateurs suivants : Wave, Moov Money, Mtn Money, Orange Money",

            "numero.required" => "Le champ numero est obligatoire.",
            "numero.string" => "Le champ numero doit être une chaîne de caractères.",

            "opt.required_if" => "Le champ le code opt est obligatoire.",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 403,
            'errors' => true,
            'message' => 'Erreur de validation',
            'errorsListe' => $validator->errors()
        ]));
    }
}
