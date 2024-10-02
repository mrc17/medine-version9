<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Gare;
use App\Models\Ticket;
use App\Models\Trajet;
use App\Models\Employe;
use App\Models\Compagnie;
use App\Models\GareCaisse;
use App\Models\Planification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Api\PayerTicketCaisseRequest;

class PayerTicketCaisseController extends Controller
{
    public function __invoke(PayerTicketCaisseRequest $request)
    {
        // Récupération des valeurs du formulaire
        $trajet_id = $request->input('trajet_id');
        $nbrplace = $request->input('nbrplace');
        $heure = $request->input('heureSelect');
        $operateur = $request->input('nomOperateur');
        $date = $request->input('date');

        // Récupération de l'utilisateur connecté
        $user = Auth::user();

        // Vérification du rôle de l'utilisateur
        if ($user->role_id !== 6) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.1',
            ], 403);
        }

        // Récupération de l'employé associé à l'utilisateur
        $employe = Employe::where('user_id', $user->id)->first();
        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.2',
            ], 403);
        }

        // Récupération de la compagnie associée à l'employé
        $compagnie = Compagnie::find($employe->compagnie_id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.3',
            ], 403);
        }

        // Récupération du trajet sélectionné
        $trajet = Trajet::find($trajet_id);
        if (!$trajet) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Trajet non trouvé.',
            ], 404);
        }

        $status = "Confirmé";

        $gareCaisse = GareCaisse::where('user_id', $user->id)->first();

        $gare = Gare::find($gareCaisse->gare_id)->first();


        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $montant_ttc = $trajet->prix + 100; // Exemple de calcul

        // Vérification de la planification
        $planification = Planification::where('trajet_id', $trajet->id)
            ->where("date", '<=', $date)
            ->where("gare_id", $gare->id)
            ->first();

        $tickets = []; // Initialisation du tableau de tickets

        for ($i = 0; $i < $nbrplace; $i++) {
            if ($planification) {
                // Enregistrer le ticket en attente
                $data = [
                    'compagnie_id' => $compagnie->id,
                    'user_id' => $user->id,
                    'depart' => $trajet->depart,
                    'arrivee' => $trajet->arrivee,
                    'date_reservation' => $date,
                    'heure_depart' => $heure,
                    "status" => $status,
                    "mode_paiement" => $operateur,
                    "gare_id" => $gare->id,
                    'codedepart' => $planification->codedepart,
                    "reference" => $this->geneReference($compagnie->sig),
                    "tarif" => $trajet->prix,
                    "numero_paiement" => $user->telephone,
                    "codeticket" => $user->telephone,
                    "montant_ttc" => $montant_ttc,
                    "num_ticket" => $this->geneNumTicket(),
                    "place" => $this->getPlace($planification),
                ];
                $tickets[] = Ticket::create($data);
            } else {
                // Enregistrer le ticket en attente
                $data = [
                    'compagnie_id' => $compagnie->id,
                    'user_id' => $user->id,
                    'depart' => $trajet->depart,
                    'arrivee' => $trajet->arrivee,
                    'date_reservation' => $date,
                    'heure_depart' => $heure,
                    "status" => "En attente",
                    "mode_paiement" => $operateur,
                    "gare_id" => $gare->id,
                    'codedepart' => null,
                    "reference" => $this->geneReference($compagnie->sig),
                    "tarif" => $trajet->prix,
                    "numero_paiement" => $user->telephone,
                    "montant_ttc" => $montant_ttc,
                    "num_ticket" => $this->geneNumTicket(),
                    "place" => null,
                ];
                $tickets[] = Ticket::create($data);
            }
        }

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            'message' => 'Paiement effectué',
            "tickets" => $this->formatData($tickets)
        ]);
    }

    // Générer une référence unique
    private function geneReference($compagnie)
    {
        do {
            $nouvelleReference = $compagnie . date("dmYHis") . "MEDINE";
            $existe = Ticket::where('reference', $nouvelleReference)->exists();
        } while ($existe);

        return $nouvelleReference;
    }

    // Générer un numéro de ticket unique
    private function geneNumTicket()
    {
        return date('Hms') . rand(0, 9999888); // Génération aléatoire
    }

    public function formatData($tickets)
    {
        $ticketsCollection = collect($tickets);

        return $ticketsCollection->map(function ($ticket) {
            return [
                "id" => $ticket->id,
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

    public function getplace($planification)
    {
        $nombreplace = $planification->car->place;
        $placesReservees = Ticket::where('codedepart', $planification->codedepart)->count();

        if ($placesReservees < $nombreplace) {
            return $placesReservees + 1;
        }

        return null; // ou une autre logique selon votre besoin
    }
}
