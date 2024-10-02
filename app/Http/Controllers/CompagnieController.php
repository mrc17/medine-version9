<?php

namespace App\Http\Controllers;

use App\Models\Compagnie;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCompagnieRequest;
use Illuminate\Support\Facades\Gate;

class CompagnieController extends Controller
{
    /**
     * Affiche la liste des compagnies.
     */
    public function index()
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('view-compagnies')) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        $compagnies = Compagnie::all();
        return view('compagnies.index', compact('compagnies'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle compagnie.
     */
    public function create()
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('create-compagnie')) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        return view('compagnies.create');
    }

    /**
     * Stocke une nouvelle compagnie dans la base de données.
     */
    public function store(CreateCompagnieRequest $request)
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('create-compagnie')) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        $compagnie = Compagnie::create($request->validated());

        return redirect()->route('compagnies.index')->with('success', 'Compagnie créée avec succès.');
    }

    /**
     * Affiche les détails de la compagnie spécifiée.
     */
    public function show(Compagnie $compagnie)
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('view-compagnie', $compagnie)) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        return view('compagnies.show', compact('compagnie'));
    }

    /**
     * Affiche le formulaire pour modifier la compagnie spécifiée.
     */
    public function edit(Compagnie $compagnie)
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('update-compagnie', $compagnie)) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        return view('compagnies.edit', compact('compagnie'));
    }

    /**
     * Met à jour les informations de la compagnie spécifiée.
     */
    public function update(Request $request, Compagnie $compagnie)
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('update-compagnie', $compagnie)) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        $request->validate([
            'nom' => 'required|string|max:255|unique:compagnies,nom,' . $compagnie->id,
            'sig' => 'required|string|max:255',
            'valide' => 'required|boolean',
            'contact' => 'required|string|max:255',
            'localite' => 'required|string|max:255',
        ]);

        $compagnie->update($request->only(['nom', 'sig', 'valide', 'contact', 'localite']));

        return redirect()->route('compagnies.index')->with('success', 'Compagnie mise à jour avec succès.');
    }

    /**
     * Supprime la compagnie spécifiée.
     */
    public function destroy(Compagnie $compagnie)
    {
        // Vérifier les autorisations d'accès
        if (Gate::denies('delete-compagnie', $compagnie)) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        $compagnie->delete();
        return redirect()->route('compagnies.index')->with('success', 'Compagnie supprimée avec succès.');
    }

}
