<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Compagnie;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer toutes les affiliations

        $affiliations = Affiliation::with(["user.role", "compagnie"])->get();
        $user = Auth::user();
        $users = User::all();
        $compagnies = Compagnie::all();
        return Inertia::render('Affiliation/Index', [
            'auth' => $user,
            "users" => $users,
            "compagnies" => $compagnies,
            'affiliations' => $affiliations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'compagnie_id' => 'required|exists:compagnies,id',
            'taux' => 'required|numeric|min:0|max:50', // Assurez-vous que taux est en pourcentage
        ]);

        // Vérifier si l'affiliation existe déjà
        $existingAffiliation = Affiliation::where('user_id', $validatedData['user_id'])
            ->where('compagnie_id', $validatedData['compagnie_id'])
            ->first();

        if ($existingAffiliation) {
            return redirect()->back()->withErrors(['affiliation' => 'Cette affiliation existe déjà.']);
        }

        // Vérifier la somme des taux d'affiliation pour la compagnie sélectionnée
        $totalTaux = Affiliation::where('compagnie_id', $validatedData['compagnie_id'])
            ->sum('taux');

        // Vérifier si la somme totale des taux ne dépasse pas 50
        if ($totalTaux + $validatedData['taux'] <= 0.5) { // Assurez-vous que taux est en pourcentage
            return redirect()->back()->withErrors(['taux' => 'La somme totale des taux d\'affiliation ne doit pas dépasser 50%.']);
        }
        $validatedData["taux"] = $validatedData['taux'] / 10;

        $validatedData["taux"] = $validatedData["taux"] / 10;

        // Créer une nouvelle affiliation
        $affiliation = Affiliation::create($validatedData);

        // Retourner une réponse de succès
        return redirect()->route("affiliations.index")
            ->with(['message' => 'Affiliation créée avec succès', 'affiliation' => $affiliation]);
    }



    /**
     * Display the specified resource.
     */
    public function show(Affiliation $affiliation)
    {
        // Authentifier l'utilisateur
        $user = Auth::user();

        // Charger les relations et retourner l'affiliation avec Inertia
        return Inertia::render('Affiliation/Show', [
            'auth' => $user,
            'affiliation' => $affiliation->load(['user', 'compagnie.tickets.user.role', 'compagnie.tickets.gare']),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Affiliation $affiliation)
    {
        // Validation des données
        $validatedData = $request->validate([
            'taux' => 'sometimes|numeric|min:0|max:0.5',
            'user_id' => 'sometimes|exists:users,id',
            'compagnie_id' => 'sometimes|exists:compagnies,id',
        ]);

        // Mettre à jour l'affiliation
        $affiliation->update($validatedData);

        // Retourner une réponse de succès
        return redirect()->route("affiliations.index")
            ->with(['message' => 'Affiliation mise à jour avec succès', 'affiliation' => $affiliation]);
    }


    public function getTotalTaux($compagnie_id)
    {
        $total = Affiliation::where('compagnie_id', $compagnie_id)
            ->sum('taux');

        return response()->json(['total' => $total]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Affiliation $affiliation)
    {
        // Supprimer l'affiliation
        $affiliation->delete();

        return redirect()->route("affiliations.index")
            ->with(['message' => 'Affiliation supprimée avec succès']);
    }
}
