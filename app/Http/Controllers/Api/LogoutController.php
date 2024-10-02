<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LogoutRequest;

class LogoutController extends Controller
{
    /**
     * Handle the user logout and token deletion.
     */
    public function __invoke(LogoutRequest $request)
    {
        // Récupérer l'utilisateur via l'user_id si nécessaire
        $user = $request->user();

        // Supprimer le token d'accès courant de l'utilisateur
        $user->currentAccessToken()->delete();

        // Retourner une réponse HTTP 204 No Content
        return response()->noContent();
    }
}
