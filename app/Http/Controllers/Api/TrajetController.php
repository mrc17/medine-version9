<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\Trajet;
use App\Models\HeureDepart;
use Illuminate\Http\Request;
use App\Models\TrajetHeureDepart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CreateTrajetRequest;

class TrajetController extends Controller
{
    public function garetrajets()
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 2)
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


        return response()->json([
            'success' => true,
            'status_code' => 200,
            'trajets' => $this->formaData($gare->trajets)
        ], 200);
    }

    public function show(Request $request)
    {
        $gare_id = $request->input('gare_id');

        if (!Gare::find($gare_id)) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'trajet' => [
                    "label" => 0,
                    "value" => 'Aucune gare trouvée',
                ],
            ], 404);
        }

        $depart = $request->input('depart');
        $arrivee = $request->input('arrivee');

        // Vérification du trajet
        $trajet = Trajet::where('depart', $depart)
                        ->where('arrivee', $arrivee)
                        ->where('gare_id', $gare_id)
                        ->first();

        if (!$trajet) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'trajet' => [
                    "label" => 0,
                    "value" => 'Aucun trajet trouvé pour ce départ et cette arrivée.',
                ],
            ], 404);
        }

        // Vérification du mode de départ
        if ($trajet->modeDepart->mode !== "Horraire") {
            return response()->json([
                'success' => true,
                'status_code' => 200,
                "montant" => $trajet->prix,
                'modeDepart' => [
                    "label" => 1,
                    "value" => "Départ après changement",
                ],
            ], 200);
        }

        // Récupération des heures de départ si mode "Horraire"
        $trajetHeureDepartIds = TrajetHeureDepart::where('trajet_id', $trajet->id)
                                                 ->pluck('heure_depart_id');

        $heures = HeureDepart::whereIn('id', $trajetHeureDepartIds)
                             ->pluck('heure');

        $heuresFormatees = $this->formaDataAchat($heures);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            "montant" => $trajet->prix,
            'modeDepart' => $heuresFormatees,
        ], 200);
    }



    public function trajetsGare($gare_id)
    {
        // Vérifier si la gare existe
        $gare = Gare::find($gare_id);
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        // Vérifier si la gare a des trajets associés
        if ($gare->trajets->isEmpty()) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'trajets' => [
                    'value' => 0,
                    'label' => 'Aucun trajet trouvé pour cette gare.',
                ],
            ]);
        }

        // Formater les départs et les arrivées en les rendant uniques
        $departs = $this->formaDataAchat($gare->trajets->pluck('depart')->unique());
        $arrivees = $this->formaDataAchat($gare->trajets->pluck('arrivee')->unique());
        // Retourner la réponse avec les données formatées
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'gare' => $gare->nom, // Retourner aussi le nom de la gare
            'trajets' => [
                'departs' => $departs,
                'arrivees' => $arrivees
            ]
        ], 200);
    }


    public function formaDataAchat($trajets)
    {
        $i = 1;

        return $trajets->map(function ($trajet) use (&$i) {
            return [
                'value' => $i++, // Si vous avez un id pour chaque trajet, utilisez-le
                'label' => $trajet, // Utilisez un champ pertinent comme 'nom' ou simplement la valeur brute
            ];
        });
    }


    public function formaData($trajets)
    {
        return $trajets->map(function ($trajet) {
            $trajetHeuresDepartIds = TrajetHeureDepart::where("trajet_id", $trajet->id)->pluck("id");
            $heures = HeureDepart::whereIn("id", $trajetHeuresDepartIds)->get()->pluck('heure');
            return [
                'value' => $trajet->id,
                'label' => $trajet->depart . " - " . $trajet->arrivee,
                "prix" => $trajet->prix,
                "mode_depart" => $trajet->modeDepart->mode,
                "status_depart" => $trajet->modeDepart->mode == "Horraire" ? $heures : "Départ après changement",
            ];
        });
    }

    public function store(CreateTrajetRequest $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 2)
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

        $existe = Trajet::where([
            "depart" => $request->input('depart'),
            "arrivee" => $request->input('arrivee'),
            "prix" => $request->input('prix'),
            "gare_id" => $gare->id,
            "mode_depart_id" => $request->input('mode_depart_id'),
        ])->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'status_code' => 203,
                'message' => 'Le trajet est déjà enregistré à cette gare.',
            ], 203);
        }
        // Création du trajet
        $trajet = Trajet::create([
            "depart" => $request->input('depart'),
            "arrivee" => $request->input('arrivee'),
            "prix" => $request->input('prix'),
            "gare_id" => $gare->id,
            "mode_depart_id" => $request->input('mode_depart_id'),
        ]);

        if ($request->input('mode_depart_id') == 2) {
            // Gestion des heures de départ
            foreach ($request->input("heures") as $value) {
                // Utilisation de firstOrCreate pour éviter les doublons
                $heure = HeureDepart::firstOrCreate(['heure' => $value]);
                // Associer l'heure au trajet
                TrajetHeureDepart::create([
                    'trajet_id' => $trajet->id, // Utiliser l'ID du trajet
                    'heure_depart_id' => $heure->id
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'status_code' => 201,
            'message' => 'Trajet créé avec succès',
        ], 201);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 2)
        if ($user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Trouver le trajet à supprimer
        $trajet = Trajet::find($id);

        // Vérifier si le trajet existe
        if (!$trajet) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Le trajet n\'a pas été trouvé.',
            ], 404);
        }

        // Supprimer le trajet sans supprimer les heures de départ associées
        $trajet->delete();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Le trajet a été supprimé avec succès.',
        ], 200);
    }

    public function updatePrice(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 2)
        if ($user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Trouver le trajet à modifier
        $trajet = Trajet::find($request->input('trajet_id'));

        // Vérifier si le trajet existe
        if (!$trajet) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Le trajet n\'a pas été trouvé.',
            ], 404);
        }

        // Valider la requête
        $request->validate([
            'prix' => ['required', 'numeric'],
        ]);

        // Mettre à jour le prix du trajet
        $trajet->prix = $request->input('prix');
        $trajet->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Le prix du trajet a été mis à jour avec succès.',
            'trajet' => [
                'id' => $trajet->id,
                'depart' => $trajet->depart,
                'arrivee' => $trajet->arrivee,
                'prix' => $trajet->prix,
            ]
        ], 200);
    }
}
