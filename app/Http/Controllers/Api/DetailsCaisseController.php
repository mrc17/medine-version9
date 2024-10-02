<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\Ticket;
use App\Models\GareCaisse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DetailsCaisseController extends Controller
{
    public function __invoke()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            if (!$this->isAuthorizedUser($user)) {
                return $this->errorResponse('Accès non autorisé.', 403);
            }

            $gare = $this->getGareByUser($user);
            if (!$gare) {
                return $this->errorResponse('Accès non autorisé à cette gare.', 403);
            }

            // Retrieve all tickets for the user
            $ticketData = Ticket::where("user_id", $user->id)
                ->select("montant_ttc", "created_at")
                ->get();

            // You can calculate the total sold amount here if needed
            $totalSold = $ticketData->sum('montant_ttc');

            return response()->json([
                'success' => true,
                'ticket' => $ticketData,
                'total_sold' => $totalSold,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des détails de la caisse : ' . $e->getMessage());
            return $this->errorResponse('Une erreur est survenue lors de la récupération des informations.', 500);
        }
    }


    private function isAuthorizedUser($user)
    {
        return $user && $user->role_id === 6;
    }

    private function getGareByUser($user)
    {
        $gareCaisse = GareCaisse::where("user_id", $user->id)->first();
        return $gareCaisse ? Gare::find($gareCaisse->gare_id) : null;
    }


    private function errorResponse($message, $statusCode)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode,
        ], $statusCode);
    }
}
