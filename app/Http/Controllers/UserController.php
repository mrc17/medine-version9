<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Gare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        // Retrieve users with their relationships
        $utilisateurs = User::with([
            'role',
            'compagnie',
            'gares_responsable',
            'gares_comptable',
            'gare_responsable',
            'gare_comptable',
            'employe',
            'tickets'
        ])->get();

        return Inertia::render('Utilisateur/Index', [
            'auth' => auth()->user(), // The authenticated user
            'utilisateurs' => $utilisateurs, // The users information
        ]);
    }

    public function delete(User $utilisateur){
        dd($utilisateur);
    }


    public function show($id) {
        // Implement show method to retrieve and display a specific user
        $utilisateur = User::with(['role', 'gares_responsable', 'gares_caisse', 'gares_comptable', 'gare_responsable', 'gare_comptable', 'employe', 'tickets'])
            ->findOrFail($id);

            dd($utilisateur);

        return Inertia::render('Utilisateur/Show', [
            'auth' => auth()->user(),
            'utilisateur' => $utilisateur,
        ]);
    }
}
