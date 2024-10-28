<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Gare;
use App\Models\Ticket;
use App\Models\Trajet;
use App\Models\Compagnie;
use App\Models\Commission;
use App\Models\Planification;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\PayerTicketRequest;

class PayerTicketController extends Controller
{
    public function __invoke(PayerTicketRequest $request)
    {
        try {
            // Correction du nom du champ "comapagnie" à "compagnie"
            $compagnie_id = Compagnie::where("sig", $request->input('compagnie'))->value('id');

            if (!$compagnie_id) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Compagnie non trouvée.',
                ], 404);
            }

            // Vérifier si l'utilisateur est authentifié
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status_code' => 401,
                    'message' => 'Utilisateur non authentifié.',
                ], 401);
            }
            $user_id = $user->id;

            // Vérifier si le trajet de départ existe
            $trajetDepart = Trajet::where('depart', $request->input('DepartVille'))->first();
            if (!$trajetDepart) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Ville de départ introuvable.',
                ], 404);
            }
            $depart = $trajetDepart->depart;

            // Vérifier si le trajet d'arrivée existe
            $trajetArrivee = Trajet::where('arrivee', $request->input('ArriverVille'))->first();
            if (!$trajetArrivee) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Ville d\'arrivée introuvable.',
                ], 404);
            }
            $arrivee = $trajetArrivee->arrivee;

            // Formater la date correctement
            $date = explode(" à ", $request->input('date'))[0];
            $heure = $request->input('heureSelect');
            $status = "Confirmeé";

            // Récupération des autres informations
            $gare_id = Gare::where("nom", $request->input("gareSelection"))->value('id');
            if (!$gare_id) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Gare introuvable.',
                ], 404);
            }

            $num_tel = $request->input("numero");

            // Vérification du trajet et du prix
            $trajet = Trajet::where([
                'depart' => $depart,
                'arrivee' => $arrivee,
                "gare_id" => $gare_id
            ])->first();

            if (!$trajet) {
                return response()->json([
                    'success' => false,
                    'status_code' => 403,
                    'message' => 'Le trajet est introuvable.',
                ], 403);
            }

            $prix = $trajet->prix;
            $operateur = $request->input("nomOperateur");

            // Vérification de la commission
            $commission = Commission::where('compagnie_id', $compagnie_id)
                ->where('montant', $prix)
                ->first();

            // Calcul du montant total TTC avec commission
            $commissionAmount = round($commission ? $commission->valeur : ($prix * 0.03));
            $montant_ttc = $commissionAmount + ($prix * $request->input('nbrTicket'));

            $formattedDate = str_replace('/', '-', $date);
            $date = Carbon::parse($formattedDate)->format('Y-m-d');

            // Vérification de la planification
            $planification = Planification::where('trajet_id', $trajet->id)
                ->where("date", '<=', $date)
                ->where("gare_id", $gare_id)
                ->first();
            for ($i = 0; $i < $request->input('nbrTicket'); $i++) {

                if ($planification) {
                    // Traitement de la commande si la planification existe
                    $data = [
                        'compagnie_id' => $compagnie_id,
                        'user_id' => $user_id,
                        'depart' => $depart,
                        'arrivee' => $arrivee,
                        'date_reservation' => $date,
                        'heure_depart' => $heure,
                        "status" => $status,
                        "mode_paiement" => $operateur,
                        "gare_id" => $gare_id,
                        'codedepart' => $planification->codedepart,
                        "reference" => $this->geneReference($request->input("compagnie")),
                        "tarif" => $prix,
                        "numero_paiement" => $num_tel,
                        "codeticket" => $num_tel,
                        "montant_ttc" => $montant_ttc/$request->input('nbrTicket'),
                        "num_ticket" => $this->geneNumTicket(),
                        "place" => $this->getplace($planification),
                    ];
                    $tickets[] = Ticket::create($data);
                } else {
                    // Enregistrer le ticket en attente
                    $data = [
                        'compagnie_id' => $compagnie_id,
                        'user_id' => $user_id,
                        'depart' => $depart,
                        'arrivee' => $arrivee,
                        'date' => $date,
                        'heure' => $heure,
                        "status" => "En attente",
                        "operateur" => $operateur,
                        "gare_id" => $gare_id,
                        'codedepart' => null,
                        "reference" => $this->geneReference($request->input("compagnie")),
                        "tarif" => $prix,
                        "num_tel" => $num_tel,
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
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur interne du serveur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function geneReference($compagnie)
    {
        do {
            // Générer une nouvelle référence avec plus d'aléatoire
            $nouvelleReference = $compagnie . date("dmYHis") . "MEDINE";
            // Vérifier si la référence existe déjà dans la base de données
            $existe = Ticket::where('reference', $nouvelleReference)->exists();
        } while ($existe);

        return $nouvelleReference;
    }


    private function geneNumTicket()
    {
        // Générer une nouvelle num_ticket
        return "000" . rand(0, 9999); // Assurez-vous que le format correspond à vos besoins
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

    public function controleOperator($operateur)
    {
        switch ($operateur) {
            case "Wave":
                return "CI_PAIEMENTWAVE_TP";
            case "Moov Money":
                return 'PAIEMENTMARCHAND_MOOV_CI';
            case "Mtn Money":
                return 'PAIEMENTMARCHAND_MTN_CI';
            case "Orange Money":
                return 'PAIEMENTMARCHANDOMPAYCIDIRECT';
            default:
                return 'PAIEMENTMARCHANDOMPAYCIDIRECT';
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
}
