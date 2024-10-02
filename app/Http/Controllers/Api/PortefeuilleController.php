<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\Compagnie;
use App\Models\Portefeuille;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\ChangeCodeWalletRequest;

class PortefeuilleController extends Controller
{
    public function unlock(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::where("responsable_id", $user->id)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }


        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        if (!$portefeuille) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Portefeuille non trouvé.',
            ], 404);
        }

        if ($portefeuille->attempt_logins >= 5) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Votre portefeuille a été bloqué. Veuillez contacter les administrateurs.',
            ], 403);
        }

        if (!Hash::check($request->input('password'), $portefeuille->password)) {
            $portefeuille->increment('attempt_logins');

            $remainingAttempts = max(5 - $portefeuille->attempt_logins, 0);

            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => "Code incorrect, il vous reste $remainingAttempts tentative(s).",
            ], 403);
        }

        // Réinitialiser le compteur de tentatives après un mot de passe correct
        $portefeuille->attempt_logins = 0;
        $portefeuille->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Portefeuille déverrouillé avec succès.',
        ]);
    }

    public function valeur()
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::where("responsable_id", $user->id)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }


        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        if (!$portefeuille) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Portefeuille non trouvé.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'commission' => $portefeuille->valeur,
            'montant_ticket' => $portefeuille->montant_ticket,
        ]);
    }

    public function historique()
    {
        $user = Auth::user();

        // Check if the user has the required role
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Get the company linked to the user
        $compagnie = Compagnie::where("responsable_id", $user->id)->first();

        // Check if the company exists
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Retrieve the tickets for the company and select the required fields
        $portefeuille = Ticket::where("compagnie_id", $compagnie->id)
            ->select("montant_ttc", "created_at")
            ->get();

        // Return the tickets
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'data' => $portefeuille,
        ], 200);
    }

    public function retrait(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::where("responsable_id", $user->id)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        if (!$portefeuille || $portefeuille->valeur < $request->montant) {
            return response()->json([
                'success' => false,
                'status_code' => 400,
                'message' => 'Solde insuffisant pour effectuer le retrait.',
            ], 400);
        }

        // Déduire le montant du portefeuille
        $portefeuille->valeur -= $request->montant;
        $portefeuille->save();

        // Logique pour enregistrer la transaction de retrait

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Retrait effectué avec succès.',
        ], 200);
    }

    public function changewalletcode(ChangeCodeWalletRequest $request)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le rôle approprié (ici 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Obtenir la compagnie liée à l'utilisateur
        $compagnie = Compagnie::where('responsable_id', $user->id)->first();

        // Vérifiez si la compagnie existe
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Obtenir le portefeuille lié à la compagnie
        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        // Vérifiez si le portefeuille existe
        if (!$portefeuille) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Le portefeuille n\'a pas été trouvé.',
            ], 404);
        }

        // Vérifiez si le code actuel correspond au code stocké dans la base de données
        if (!Hash::check($request->input('password'), $portefeuille->password)) {
            $portefeuille->increment('attempt_logins');

            $remainingAttempts = max(5 - $portefeuille->attempt_logins, 0);

            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => "Code incorrect, il vous reste $remainingAttempts tentative(s).",
            ], 403);
        }

        // Remise à zéro des tentatives si le code est correct
        $portefeuille->attempt_logins = 0;

        // Mettre à jour le code de portefeuille avec le nouveau code (haché)
        $portefeuille->password = Hash::make($request->input('new_password'));
        $portefeuille->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Le code de portefeuille a été changé avec succès.',
        ], 200);
    }
}
