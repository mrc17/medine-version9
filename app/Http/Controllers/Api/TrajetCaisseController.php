<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\Ticket;
use App\Models\GareCaisse;
use App\Models\HeureDepart;
use Illuminate\Http\Request;
use App\Models\Planification;
use App\Models\TrajetHeureDepart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TrajetCaisseController extends Controller
{
    /**
     * Récupère les informations des trajets pour l'utilisateur authentifié.
     */
    public function infotrjets()
    {
        try {
            $user = Auth::user();

            if (!$this->isAuthorizedUser($user)) {
                return $this->errorResponse('Accès non autorisé.', 403);
            }

            $gare = $this->getGareByUser($user);
            if (!$gare) {
                return $this->errorResponse('Accès non autorisé à cette gare.', 403);
            }

            return $this->successResponse($this->formatTrajetData($gare->trajets, $gare));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des trajets : ' . $e->getMessage());
            return $this->errorResponse('Une erreur est survenue lors de la récupération des informations.', 500);
        }
    }

    /**
     * Vérifie si l'utilisateur est autorisé.
     */
    private function isAuthorizedUser($user)
    {
        return $user && $user->role_id === 6;
    }

    /**
     * Récupère la gare associée à l'utilisateur.
     */
    private function getGareByUser($user)
    {
        $gareCaisse = GareCaisse::where("user_id", $user->id)->first();
        return $gareCaisse ? Gare::find($gareCaisse->gare_id) : null;
    }

    /**
     * Formate les trajets et leurs planifications.
     */
    private function formatTrajetData($trajets, $gare)
    {
        $currentDateTime = Carbon::now();

        return $trajets->map(function ($trajet) use ($gare, $currentDateTime) {
            $heures = $this->getHeuresDepart($trajet);
            $planifications = $this->getPlanifications($trajet, $gare, $currentDateTime);

            return [
                'value' => $trajet->id,
                'label' => "{$trajet->depart} - {$trajet->arrivee}",
                'prix' => $trajet->prix,
                'mode_depart' => $trajet->modeDepart->mode,
                'status_depart' => $trajet->modeDepart->mode === "Horraire" ? $heures : "Départ après changement",
                'planifications' => $this->formatPlanifications($planifications),
            ];
        });
    }

    /**
     * Récupère les heures de départ pour un trajet.
     */
    private function getHeuresDepart($trajet)
    {
        return TrajetHeureDepart::where("trajet_id", $trajet->id)
            ->with('heureDepart')
            ->get()
            ->pluck('heureDepart.heure');
    }

    /**
     * Récupère les planifications valides pour un trajet et une gare donnés.
     */
    private function getPlanifications($trajet, $gare, $currentDateTime)
    {
        return Planification::where('trajet_id', $trajet->id)
            ->where('gare_id', $gare->id)
            ->where(function ($query) use ($currentDateTime) {
                $query->where('date', '>', $currentDateTime->format('Y-m-d'))
                    ->orWhere(function ($subQuery) use ($currentDateTime) {
                        $subQuery->where('date', '=', $currentDateTime->format('Y-m-d'))
                            ->where('heure', '>=', $currentDateTime->format('H:i:s'));
                    });
            })
            ->get();
    }

    /**
     * Formate les planifications pour un trajet.
     */
    private function formatPlanifications($planifications)
    {
        return $planifications->map(function ($planification) {
            return [
                'codedepart' => $planification->codedepart,
                'heure' => $planification->heure,
                'date' => $planification->date,
                "place" => $this->calculatePlace($planification),
            ];
        });
    }

    /**
     * Calcule les places restantes pour une planification donnée.
     */
    public function calculatePlace($planification)
    {
        $totalPlaces = $planification->car->place;
        $placesReservees = Ticket::where('codedepart', $planification->codedepart)->count();

        return $placesReservees < $totalPlaces ? $totalPlaces - $placesReservees : 0;
    }

    /**
     * Réponse JSON de succès.
     */
    private function successResponse($data, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'status_code' => $statusCode,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Réponse JSON d'erreur.
     */
    private function errorResponse($message, $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'status_code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Méthode pour récupérer les informations d'un trajet spécifique.
     */
    public function infotrjet(Request $request)
    {
        try {
            $date = $request->input('date');
            $heure = $request->input('heure');
            $trajet_id = $request->input('trajet_id');

            $user = Auth::user();

            if (!$this->isAuthorizedUser($user)) {
                return $this->errorResponse('Accès non autorisé.', 403);
            }

            $gare = $this->getGareByUser($user);
            if (!$gare) {
                return $this->errorResponse('Accès non autorisé à cette gare.', 403);
            }

            $planification = Planification::where([
                'gare_id' => $gare->id,
                'trajet_id' => $trajet_id,
                'date' => $date,
                'heure' => $heure,
            ])->first();

            return $this->successResponse(['siegere' => $planification ? $this->calculatePlace($planification) : 0]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du trajet : ' . $e->getMessage());
            return $this->errorResponse('Une erreur est survenue lors de la récupération des informations.', 500);
        }
    }
}
