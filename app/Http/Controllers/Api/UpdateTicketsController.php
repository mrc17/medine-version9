<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\Planification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UpdateTicketsController extends Controller
{
    public function __invoke()
    {
        try {
            // Obtenez toutes les planifications disponibles
            $planifications = Planification::where('date', '>=', date('Y-m-d'))->get();

            foreach ($planifications as $planification) {
                // Obtenez les tickets en attente pour cette planification
                $tickets = Ticket::where('status', 'En attente')
                    ->where('depart', $planification->depart)
                    ->where('arrivee', $planification->arrivee)
                    ->where('date', $planification->date)
                    ->where('gare_id', $planification->gare_id)
                    ->get();

                foreach ($tickets as $ticket) {
                    $nombrePlace = $planification->car->place;
                    $placesDisponibles = $nombrePlace - Ticket::where('codedepart', $planification->codedepart)->count();

                    if ($placesDisponibles > 0) {
                        // Mettre à jour le ticket avec le numéro de place et le code départ
                        $ticket->update([
                            'status' => 'Confirmé',
                            'codedepart' => $planification->codedepart,
                            'siege' => $nombrePlace - $placesDisponibles + 1,
                        ]);
                    }
                }
            }

            return response()->json(['message' => 'Tickets updated successfully']);
        } catch (\Exception $e) {
            // Log des erreurs
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating tickets'], 500);
        }
    }
}
