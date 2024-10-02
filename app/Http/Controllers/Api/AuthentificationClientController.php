<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Telephone;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\AuthentificationAdminRequest;

class AuthentificationClientController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Api\AuthentificationAdminRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(AuthentificationAdminRequest $request): JsonResponse
    {
        // Récupérer les données validées de la requête
        $validated = $request->validated();

        // Recherche de l'utilisateur par rôle et téléphone
        $user = User::where("role_id", 5)
            ->oRwhere("role_id", 6)
            ->where('telephone', $validated['telephone'])
            ->first();

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Identifiants incorrects'], 401);
        }

        // Vérifier si le compte est verrouillé (état = 0)
        if ($user->etat == 0) {
            return response()->json(['success' => false, 'message' => 'Votre compte a été verrouillé'], 423);
        }

        // Vérifier si le nombre d'essais est dépassé
        if ($user->attempt_logins >= 5) {
            return response()->json(['success' => false, 'message' => 'Votre compte a été bloqué en raison de trop nombreuses tentatives'], 403);
        }

        // Vérifier le mot de passe
        if (Hash::check($validated['password'], $user->password)) {
            // Réinitialiser les tentatives après une connexion réussie
            $user->attempt_logins = 0;
            $user->save();

            // Vérification du rôle et envoi d'un code SMS si nécessaire
            if ($user->role_id == 6) {
                // Générer un code SMS
                $code = $this->genereCodeToSms();

                // Enregistrer le code dans la base de données et l'envoyer par SMS
                $telephoneVerification = $this->sendCodeToSmsAndSaveDb($user->telephone, $code);

                // Retourner une réponse JSON de succès
                return response()->json([
                    'success' => true,
                    "status_code"=>503,
                    'message' => 'Authentification réussie, un code a été envoyé par SMS.',
                    'telephone_id' => $telephoneVerification->id,
                    'code' => $code,
                ]);
            }

            // Générer un token API pour l'utilisateur
            $token = $user->createToken('auth_token')->plainTextToken;

            // Retourner une réponse avec le token
            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => [
                    "nom" => $user->nom,
                    "prenom" => $user->prenom,
                    "telephone" => $user->telephone,
                    'token' => $token,
                ],
            ], 200);
        }

        // Si le mot de passe est incorrect, incrémenter le nombre de tentatives
        $user->attempt_logins++;
        $user->save();

        return response()->json(['success' => false, 'message' => 'Mot de passe incorrect'], 401);
    }

    // Méthode pour générer un code aléatoire de 5 chiffres
    private function genereCodeToSms()
    {
        return random_int(10000, 99999); // Utilisation d'un code de 5 chiffres
    }

    // Méthode pour envoyer le code par SMS et l'enregistrer dans la base de données
    private function sendCodeToSmsAndSaveDb($telephone, $code)
    {
        // Envoi du code par SMS (la logique dépendra du fournisseur de SMS)
        // Exemple : SmsService::send($telephone, "Votre code est : $code");

        // Enregistrement du code dans la table 'telephones'
        $telephoneVerification = Telephone::create([
            'numero' => $telephone,
            'code' => bcrypt($code), // Utilisation de bcrypt pour sécuriser le code
        ]);

        return $telephoneVerification;
    }
}
