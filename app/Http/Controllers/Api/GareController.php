<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Employe;
use App\Models\Compagnie;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\CreateGareRequest;

class GareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Implementation for listing resources (if needed)
    }

    public function compagniegare($compagnie_id)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::find($compagnie_id);

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $gares = $compagnie->gares;

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Compagnie et gares récupérées avec succès.',
            'gares' => $this->formaData($gares)
        ], 200);
    }

    protected function formaData($gares)
    {
        return $gares->map(function ($gare) {
            return [
                'value' => $gare->id,
                'label' => $gare->nom,
                'id_responsable' => $gare->responsable_gare ? $gare->responsable_gare->id : '',
                'nom_responsable' => $gare->responsable_gare ? $gare->responsable_gare->nom : '',
                'prenom_responsable' => $gare->responsable_gare ? $gare->responsable_gare->prenom : '',
                'num_responsable' => $gare->responsable_gare ? $gare->responsable_gare->telephone : '',
                'id_comptable' => $gare->comptable ? $gare->comptable->id : '',
                'nom_comptable' => $gare->comptable ? $gare->comptable->nom : '',
                'prenom_comptable' => $gare->comptable ? $gare->comptable->prenom : '',
                'num_comptable' => $gare->comptable ? $gare->comptable->telephone : '',
                "valide" => $gare->valide
            ];
        });
    }

    public function changeresponsable($compagnie_id, $id_responsable, $id_gare)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::find($compagnie_id);

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $gare = Gare::find($id_gare);

        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        $gare->update([
            'responsable_gare_id' => $id_responsable
        ]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Responsable de la gare mis à jour avec succès.',
        ], 200);
    }

    public function changecomptable($compagnie_id, $id_comptable, $id_gare)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::find($compagnie_id);

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $gare = Gare::find($id_gare);

        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        $gare->update([
            'comptable_id' => $id_comptable
        ]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Comptable de la gare mis à jour avec succès.',
        ], 200);
    }

    public function etat($compagnie_id, $id_gare)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($compagnie_id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Vérifier si la gare existe
        $gare = Gare::find($id_gare);
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        // Basculez l'état de validité de la gare
        $gare->valide = !$gare->valide;
        $gare->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => $gare->valide ? 'La gare a été activée avec succès.' : 'La gare a été suspendue avec succès.',
        ], 200);
    }


    public function store(CreateGareRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $responsable_gare = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $data['telephone'],
                'password' => Hash::make($data['password']),
                'role_id' => 2,
            ]);

            $comptable = User::create([
                'nom' => $data['nomFinancing'],
                'prenom' => $data['prenomFinancing'],
                'telephone' => $data['telephoneFinancing'],
                'password' => Hash::make($data['passwordFinancing']),
                'role_id' => 3,
            ]);

            $gare = Gare::create([
                'nom' => $data['gare'],
                'ville' => $data['ville'],
                'commune' => $data['commune'],
                'responsable_gare_id' => $responsable_gare->id,
                'comptable_id' => $comptable->id,
                'compagnie_id' => $data['compagnie_id'],
                'valide' => true,
            ]);

            Employe::create([
                'user_id' => $gare->responsable_gare_id,
                'compagnie_id' => $gare->compagnie_id,
            ]);

            Employe::create([
                'user_id' => $gare->comptable_id,
                'compagnie_id' => $gare->compagnie_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Gare créée avec succès.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur lors de la création de la gare.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($gare_id)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le droit d'accéder à cette gare
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }


        $gare = Gare::find($gare_id);

        // Vérifiez que la gare existe
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifiez si l'utilisateur est le responsable de la compagnie associée à la gare
        $compagnie = $gare->compagnie;


        if ($compagnie->id !== $gare->compagnie_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        try {
            // Exemple : récupérer les tickets du jour pour cette gare
            $tickets = Ticket::where('gare_id', $gare->id)
                ->where('date_reservation', date('Y-m-d'))
                ->get();

            // Calcul du montant total des tickets vendus pour cette gare aujourd'hui
            $montantTotal = $tickets->sum('tarif');

            $gare = Gare::find($gare_id);

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'gare' => $gare,
                'tickets' => $this->formatData($tickets),
                'montant_total' => $montantTotal,
                'message' => 'Données de la gare récupérées avec succès.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur lors de la récupération des données de la gare.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showhistorique($gare_id)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le droit d'accéder à cette gare
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }


        $gare = Gare::find($gare_id);

        // Vérifiez que la gare existe
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifiez si l'utilisateur est le responsable de la compagnie associée à la gare
        $compagnie = $gare->compagnie;


        if ($compagnie->id !== $gare->compagnie_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        try {

            $tickets = $gare->tickets;

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'gare' => $gare,
                'tickets' => $this->formatData($tickets),
            ], 200);
        } catch (\Throwable $error) {
            //throw $th;
        }
    }

    public function compagniegaretickets($gare_id)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le droit d'accéder à cette gare
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }


        $gare = Gare::find($gare_id);

        // Vérifiez que la gare existe
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifiez si l'utilisateur est le responsable de la compagnie associée à la gare
        $compagnie = $gare->compagnie;


        if ($compagnie->id !== $gare->compagnie_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        try {
            // Exemple : récupérer les tickets du jour pour cette gare
            $tickets = Ticket::where('gare_id', $gare->id)
                ->orderBy('id', "DESC")
                ->get();


            return response()->json([
                'success' => true,
                'status_code' => 200,
                'tickets' => $this->formatData($tickets),
                'message' => 'Données de la gare récupérées avec succès.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur lors de la récupération des données de la gare.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function formatData($tickets)
    {
        $ticketsCollection = collect($tickets);

        return $ticketsCollection->map(function ($ticket) {
            return [
                "id" => $ticket->id,
                'nom_compagnie' => $ticket->compagnie->sig,
                'nom_compagnie' => $ticket->compagnie->nom,
                'path' => $ticket->compagnie->image,
                "nom" => $ticket->user->nom,
                "prenom" => $ticket->user->prenom,
                'depart' => $ticket->depart,
                'arrivee' => $ticket->arrivee,
                'date' => $ticket->date_reservation,
                'mode_paiement' => $ticket->mode_paiement,
                'status' => $ticket->status,
                'heure' => $ticket->heure_depart,
                "status" => $ticket->status,
                "gare_id" => $ticket->gare->nom,
                "reference" => $ticket->reference,
                "tarif" => $ticket->tarif,
                "num_tel" => $ticket->numero_paiement,
                "montant_ttc" => $ticket->montant_ttc,
                "num_ticket" => $ticket->num_ticket,
                "siege" => $ticket->place,
            ];
        });
    }

    public function getFileContent($filePath)
    {
        // Vérifier si le fichier existe
        if (File::exists(storage_path('app/public/' . $filePath))) {
            // Lire le fichier
            $fileContent = File::get(storage_path('app/public/' . $filePath));

            return ($fileContent);
        }

        // Si le fichier n'existe pas, retourner un message ou une image par défaut
        return 'File not found';
    }


    public function destroy(Gare $gare)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le droit de supprimer cette gare
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifiez que la gare existe
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La gare n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifiez si l'utilisateur est le responsable de la compagnie associée à la gare
        $compagnie = $gare->compagnie;

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à supprimer cette gare.',
            ], 403);
        }

        try {
            // Supprimez la gare
            $gare->delete();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'La gare a été supprimée avec succès.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur lors de la suppression de la gare.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function compagniegares($compagnie_id)
    {
        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($compagnie_id);

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Compagnie non trouvée.',
            ], 404);
        }

        // Récupérer les gares associées à la compagnie
        $gares = Gare::where("compagnie_id", $compagnie_id)->get();

        if ($gares->isEmpty()) {
            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Aucune gare trouvée pour cette compagnie.',
                'gares' => []
            ], 200);
        }

        // Retourner les gares formatées
        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Compagnie et gares récupérées avec succès.',
            'gares' => $this->formaDataGare($gares)
        ], 200);
    }

    protected function formaDataGare($gares)
    {
        return $gares->map(function ($gare) {
            return [
                'value' => $gare->id,
                'label' => $gare->nom,
                'ville' => $gare->ville,
                'commune' => $gare->commune,
            ];
        });
    }
}
