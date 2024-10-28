<?php

namespace App\Http\Controllers;

use App\Models\Compagnie;
use App\Models\Gare;
use App\Models\Ticket;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashbordadmin()
    {
        // Récupérer toutes les données
        $users = User::all(); // Tous les utilisateurs
        $tickets = Ticket::with(['user', 'compagnie', 'gare'])->orderBy("updated_at", "DESC")->get(); // Tous les tickets avec relations
        $compagnies = Compagnie::with('tickets')->orderBy("updated_at", "DESC")->get(); // Toutes les compagnies avec tickets
        $gares = Gare::orderBy("updated_at", "DESC")->get(); // Toutes les gares, correction de l'ordre

        return Inertia::render('Dashboard', [
            "stats" => [
                "users" => $users,
                "tickets" => $tickets,
                "compagnies" => $compagnies,
                "gares" => $gares,
                "pays" => 4, // Supposons que ça reste constant ou que tu le calcules ailleurs
            ],
            "auth" => auth()->user(),
        ]);
    }

    public function dashboardEmploye()
    {
        return Inertia::render('Dashboard');
    }
}
