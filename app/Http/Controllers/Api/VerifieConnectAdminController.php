<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\User;
use App\Models\Employe;
use App\Models\Telephone;
use App\Models\GareCaisse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\VerifieTelephoneRequest;

class VerifieConnectAdminController extends Controller
{
    public function __invoke(VerifieTelephoneRequest $request)
    {
        // Récupérer la vérification du téléphone par ID
        $telephoneVerification = Telephone::find($request->input('telephone_id'));

        if (!$telephoneVerification) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Code incorrect ou non trouvé.',
            ], 404);
        }

        if ($telephoneVerification->valide) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => 'Code déjà vérifié.',
            ], 400);
        }

        // Vérification du code
        if (!Hash::check($request->input('code'), $telephoneVerification->code)) {
            $user = User::where("telephone", $telephoneVerification->numero)
                ->whereIn('role_id', [1, 2, 3, 6])
                ->first();

            if ($user) {
                $user->increment('attempt_logins');
            }

            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => "Code incorrect. Il vous reste " . (5 - $user->attempt_logins) . " essais avant que votre compte soit bloqué.",
            ], 400);
        }

        // Authentification de l'utilisateur
        $user = User::where("telephone", $telephoneVerification->numero)->first();

        $user->update([
            "attempt_logins" => 0
        ]);

        Auth::login($user);

        // Marquer le code comme validé
        $telephoneVerification->update(['valide' => true]);

        // Créer un token d'accès personnel pour l'utilisateur
        $token = $user->createToken('AuthToken')->plainTextToken;

        if ($user->role_id == 1) {
            // Retourner une réponse JSON en cas de succès avec la compagnie, le rôle et le token
            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie',
                'utilisateur' => [
                    'token' => $token,
                    "nom" => $user->nom,
                    "user_id" => $user->id,
                    "prenom" => $user->prenom,
                    'role' => $user->role->nom,
                    'sig' => $user->compagnie->sig ?? "",
                    "telephone" => $user->telephone,
                    "compagnie_id" => $user->compagnie->id ?? "",
                    'contact' => $user->compagnie->contact ?? "",
                    'nomcompagnie' => $user->compagnie->nom ?? "",
                ]
            ], 200);
        } elseif ($user->role_id == 2) {
            $gare = Gare::where('responsable_gare_id', $user->id)->first();
            $employe = Employe::where('user_id', $user->id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie',
                'utilisateur' => [
                    'token' => $token,
                    "nom" => $user->nom,
                    "user_id" => $user->id,
                    "prenom" => $user->prenom,
                    'role' => $user->role->nom,
                    "telephone" => $user->telephone,
                    "gare" => $gare->nom ?? "",
                    "gare_id" => $gare->id ?? "",
                    "ville" => $gare->ville ?? "",
                    "commune" => $gare->commune ?? "",
                    "compagnie_id" => $employe->compagnie->id ?? "",
                    "nomcompagnie" => $employe->compagnie->nom ?? "",
                    "sig" => $employe->compagnie->sig ?? "",
                    'contact' => $employe->compagnie->contact ?? "",
                ]
            ], 200);
        } elseif ($user->role_id == 6) {
            $gare_id = GareCaisse::where('user_id', $user->id)->first();
            $gare = Gare::find($gare_id)->first();
            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie',
                'utilisateur' => [
                    'token' => $token,
                    "nom" => $user->nom,
                    "user_id" => $user->id,
                    "prenom" => $user->prenom,
                    'role' => $user->role->nom,
                    "telephone" => $user->telephone,
                    "gare" => $gare->nom ?? "",
                    "gare_id" => $gare->id ?? "",
                    "ville" =>$gare->ville ?? "",
                    "commune" => $gare->commune ?? "",
                    "sig" => $gare->compagnie->sig ?? "",
                    "compagnie_id" => $gare->compagnie->id ?? "",
                    "nomcompagnie" => $gare->compagnie->nom ?? "",
                    'contact' => $gare->compagnie->contact ?? "",
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie',
                'utilisateur' => [
                    'token' => $token,
                    "nom" => $user->nom,
                    "user_id" => $user->id,
                    "prenom" => $user->prenom,
                    'role' => $user->role->nom,
                    "telephone" => $user->telephone,
                    "gare" => $user->gare_comptable->nom ?? "",
                    "gare_id" => $user->gare_comptable->id ?? "",
                    "ville" => $user->gare_comptable->ville ?? "",
                    "commune" => $user->gare_comptable->commune ?? "",
                    "sig" => $user->gare_comptable->compagnie->sig ?? "",
                    "compagnie_id" => $user->gare_comptable->compagnie->id ?? "",
                    "nomcompagnie" => $user->gare_comptable->compagnie->nom ?? "",
                    'contact' => $user->gare_comptable->compagnie->contact ?? "",
                ]
            ], 200);
        }
    }
}
