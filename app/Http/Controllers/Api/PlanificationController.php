<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Gare;
use App\Models\HeureDepart;
use App\Models\Planification;
use App\Models\TrajetHeureDepart;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CreatePlanificationRequest;

class PlanificationController extends Controller
{
    public function store(CreatePlanificationRequest $request)
    {
        $user = Auth::user();

        // Vérification du rôle utilisateur
        if ($user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la gare existe
        $gare = Gare::where("responsable_gare_id", $user->id)->first();
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        $codedepart = rand(1000000, 999999999999999);

        foreach ($request->input('planification') as $item) {
            // Vérification des doublons
            $existingPlanification = Planification::where([
                ['car_id', '=', $request->input('car_id')],
                ['gare_id', '=', $gare->id],
                ['trajet_id', '=', $item['trajet_id']],
                ['date', '=', $request->input('date')],
                ['heure', '=', $request->input('heure')]
            ])->first();

            if ($existingPlanification) {
                return response()->json([
                    'success' => false,
                    'status_code' => 409,
                    'message' => 'Une planification avec ces critères existe déjà pour ce jour.',
                ], 409);
            }

            $planification = [
                'car_id' => $request->input('car_id'),
                'gare_id' => $gare->id,
                'trajet_id' => $item['trajet_id'],
                'date' => $request->input('date'),
                'codedepart' => $codedepart,
                'heure' => $request->input('heure')
            ];

            Planification::create($planification);
        }

        return response()->json([
            'success' => true,
            'status_code' => 201,
            'message' => 'La planification a été créée avec succès.',
        ], 201);
    }


    public function gareplanifications()
    {
        $user = Auth::user();

        // Vérification du rôle utilisateur
        if ($user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la gare existe
        $gare = Gare::where("responsable_gare_id", $user->id)->first();
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        // Regrouper les planifications par date
        $planificationsGroupedByDate = $gare->planifications->groupBy(function ($planification) {
            // Convertir la date en format 'YYYY-MM-DD'
            return Carbon::parse($planification->date)->format('Y-m-d');
        });

        // Formater les dates pour le composant Calendar avec les détails supplémentaires
        $formattedPlanifications = $planificationsGroupedByDate->mapWithKeys(function ($planifications, $date) {
            return [
                $date => [
                    'selected' => true,  // Exemple : Marquer la date comme sélectionnée
                    'marked' => true,    // Exemple : Marquer la date
                    'selectedColor' => $date < Carbon::today()->format('Y-m-d') ? 'lightgray' : 'blue', // Couleur conditionnelle
                    'planification' => $planifications->map(function ($planification) {
                        $trajetHeuresDepartIds = TrajetHeureDepart::where("trajet_id", $planification->trajet->id)->pluck("id");
                        $heures = HeureDepart::whereIn("id", $trajetHeuresDepartIds)->get()->pluck('heure');
                        return [
                            "id" => $planification->id,
                            "depart" => $planification->trajet->depart,
                            "arrivee" => $planification->trajet->arrivee,
                            "prix" => $planification->trajet->prix,
                            "mode_depart" => $planification->trajet->modeDepart->mode,
                            "status_depart" => $planification->trajet->modeDepart->mode == "Horraire" ? $planification->heure : "Départ après changement",
                            "nombre_de_place" => $planification->car->place,
                            "numero_plaque" => $planification->car->imatriculation,
                        ];
                    }),
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'planifications' => $formattedPlanifications,
        ], 200);
    }

    public function planificationvalide($gare_id)
    {
        // Récupérer les planifications dont la date est inférieure ou égale à la date actuelle
        $planifications = Planification::where('gare_id', $gare_id)
            ->whereDate("date", '>=', date('Y-m-d'))
            ->get();  // Exécuter la requête et récupérer les résultats

        // Vérification du résultat (optionnel)
        if ($planifications->isEmpty()) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Aucune planification valide trouvée pour cette gare.'
            ], 404);
        }

        // Retourner les planifications trouvées
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'planifications' => $this->formData($planifications),
        ], 200);
    }


    public function formData($planifications)
    {
        return $planifications->map(function ($planification) {
            return [
                'value' => $planification->id,
                'date' => $planification->date,
                'heure' => $planification->heure,
                'prix' => $planification->trajet->prix,
                'depart' => $planification->trajet->depart,
                'arrive' => $planification->trajet->arrivee,
            ];
        });
    }
}
