<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\AuthentificationAdminRequest;
use App\Models\Telephone;

class AuthentificationAdminController extends Controller
{
    public function __invoke(AuthentificationAdminRequest $request)
    {
        // Récupération des informations de connexion depuis la requête validée
        $credentials = $request->only('telephone', 'password');

        // Rechercher l'utilisateur par son téléphone et vérifier le role_id
        $user = User::where('telephone', $credentials['telephone'])
            ->whereIn('role_id', [1, 2, 3])
            ->with('compagnie', 'role') // Inclure la compagnie et le rôle
            ->first();

        if (!$user) {
            // Retourner une réponse JSON en cas d'échec
            return response()->json([
                'success' => false,
                'message' => 'Échec de l\'authentification, vérifiez vos informations d\'identification'
            ], 401);
        }

        if ($user->role_id == 1 && $user?->compagnie->valide == 0) {
            return response()->json([
                'success' => false,
                "status_code" => 401,
                'message' => 'Votre compagnie est en cours de vérification.'
            ]);
        }

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Générer un code SMS
            $code = $this->genereCodeToSms();

            // Enregistrer le code dans la base de données et l'envoyer par SMS
            $telephone = $this->sendCodeToSmsAndSaveDb($user->telephone, $code);

            // Retourner une réponse JSON de succès
            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie, un code a été envoyé par SMS.',
                'telephone_id' => $telephone->id,
                'code' => $code,
            ]);
        } else {
            // Retourner une réponse JSON en cas d'échec
            return response()->json([
                'success' => false,
                'message' => 'Échec de l\'authentification, vérifiez vos informations d\'identification'
            ], 401);
        }
    }

    // Méthode pour générer un code aléatoire de 6 chiffres
    private function genereCodeToSms()
    {
        return rand(10000, 99999);
    }

    // Méthode pour envoyer le code par SMS et l'enregistrer dans la base de données
    private function sendCodeToSmsAndSaveDb($telephone, $code)
    {
        // Envoi du code par SMS (la logique dépendra du fournisseur de SMS)
        // Exemple : SmsService::send($telephone, "Votre code est : $code");

        // Enregistrement du code dans la table 'telephones'
        $telephoneVerification = Telephone::create([
            'numero' => $telephone,
            'code' => bcrypt($code),
        ]);

        return $telephoneVerification;
    }
}
