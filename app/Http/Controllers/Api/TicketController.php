<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\Ticket;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Api\TicketScannerRequest;

class TicketController extends Controller
{
    public function ticketuser()
    {
        $user = Auth::user();

        // Récupération et tri des tickets
        $tickets = $user->tickets()->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
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

    public function compagnietickets()
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::where('responsable_id', $user->id)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Récupération et tri des tickets
        $tickets = $compagnie->tickets()->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
    }

    public function garstickets()
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

        // Récupération et tri des tickets
        $tickets = $gare->tickets()->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
    }

    public function ticketscanner(TicketScannerRequest $request)
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

        $ticket = Ticket::find($request->input('ticket_id'));

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Ticket invalide',
            ], 404);
        }

        if ($ticket->gare_id !== $gare->id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Ticket invalide',
            ], 403);
        }

        if ($ticket->status === 'Expiré') {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Ticket expiré',
            ], 422);
        }

        if ($ticket->status === 'En attente') {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Ticket en attente, veuillez planifier un voyage correspondant au ticket',
            ], 422);
        }

        if ($ticket->status === 'Scanné') {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Ticket déjà scanné',
            ], 422);
        }

        $ticket->update(["status" => "Scanné"]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Ticket valide, bon voyage',
        ], 200);
    }

    public function ticketsByDayForGare(Request $request)
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

        $tickets = $gare->tickets()
            ->where('date_reservation', '=', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
    }
    public function ticketsScanneByDayForGare(Request $request)
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

        $tickets = $gare->tickets()
            ->where('date_reservation', '=', date('Y-m-d'))
            ->where('status', '=',"Scanné")
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
    }

    public function ticketsByDayForCompagnie(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = Compagnie::where('responsable_id', $user->id)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        $tickets = $compagnie->tickets()
            ->where('date_reservation', '=', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "tickets" => $this->formatData($tickets)
        ]);
    }

    public function ticketsRevenueByDayForGare(Request $request)
    {
        $user = Auth::user();

        // Vérification du rôle utilisateur
        if ($user->role_id !== 3) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la gare existe
        $gare = Gare::where("comptable_id", $user->id)->first();
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        $total_revenue = $gare->tickets()
            ->selectRaw('date_reservation, SUM(tarif) as total_revenue')
            ->groupBy('date_reservation')
            ->orderBy('date_reservation', 'ASC')
            ->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "total_revenue" => $this->formatDataRevenue($total_revenue)
        ]);
    }
    public function ticketsRevenueByAujourdhuiForGare()
    {
        $user = Auth::user();

        // Vérification du rôle utilisateur
        if ($user->role_id !== 3) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la gare existe
        $gare = Gare::where("comptable_id", $user->id)->first();
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette gare.',
            ], 403);
        }

        $total_revenue = $gare->tickets()
            ->selectRaw('date_reservation, SUM(tarif) as total_revenue')
            ->groupBy('date_reservation')
            ->where('date_reservation',"=",date("Y-m-d"))
            ->orderBy('date_reservation', 'ASC')
            ->first();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'errors' => false,
            "total_revenue" => $total_revenue ?? 0
        ]);
    }

    public function formatDataRevenue($datas)
    {
        // Transformer la collection en un tableau où chaque clé est une date, et chaque valeur est l'objet attendu par le calendrier
        $formattedData = [];

        foreach ($datas as $data) {
            $formattedData[$data->date_reservation] = [
                'selected' => true,
                'marked' => true,
                'selectedColor' => '#071949',
                'total_revenue' => $data->total_revenue
            ];
        }

        return $formattedData;
    }

}
