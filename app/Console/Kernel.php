<?php

namespace App\Console;

use App\Models\Ticket;
use App\Models\Planification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Exécuter tous les jours à 00:00
        $schedule->call(function () {
            // Récupération des tickets expirés
            $tickets = Ticket::where('date_reservation', '<=', date('Y-m-d'))
                ->orWhere('status', 'Confirmé') // Correction de l'orthographe
                ->orWhere('status', 'En attente') // Correction de l'orthographe
                ->orWhere('status', 'Scanné') // Correction de l'orthographe
                ->get();

            // Mise à jour des tickets expirés
            foreach ($tickets as $ticket) {
                $ticket->update(['status' => 'Expiré']);
            }
        })->daily('00:00'); // Exécuter chaque jour à minuit


        // Exécuter toutes les 5 minutes
        $schedule->call(function () {
            // Obtenez toutes les planifications disponibles
            $planifications = Planification::where('date', '>=', date('Y-m-d'))->get();

            foreach ($planifications as $planification) {
                // Obtenez les tickets en attente pour cette planification
                $tickets = Ticket::where('status', 'En attente')
                    ->where('depart', $planification->depart)
                    ->where('arrivee', $planification->arrivee)
                    ->where('date_reservation', $planification->date)
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
        })->everyMinute(); // Exécuter toutes les 5 minutes
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
