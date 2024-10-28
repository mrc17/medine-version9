<?php

namespace App\Http\Controllers;

use App\Models\Gare;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class GareController extends Controller
{
    public function show(Gare $gare)
    {
        $gare = Gare::with([
            'compagnie',
            'tickets.user',
            'comptable.role',
            'comptable.gare_comptable',
            'planifications.car',
            'trajets.modeDepart',
            'caissiers.user.role',
            'responsable_gare.role',
            'responsable_gare.gare_responsable',
            'planifications.trajet',
            'trajets.trajetHeuresDepart',
            'caissiers.user.gares_caisse.gare',
        ])->find($gare->id); // Using find instead of where() for clarity

        return Inertia::render('Gare/Show', [
            'auth' => auth()->user(), // The authenticated user
            'gare' => $gare,           // The station information
        ]);
    }

    public function index()
    {
        $gares = Gare::with([
            'responsable_gare',
            'comptable',
            'compagnie',
        ])->get(); // Use get() instead of all()

        return Inertia::render('Gare/Index', [
            'auth' => auth()->user(), // The authenticated user
            'gares' => $gares,        // The stations information
        ]);
    }

    public function update(Request $request, Gare $gare)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('gares')->ignore($gare->id),
            ],
            'ville' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
        ]);

        $gare->update($validatedData);

        return redirect()->route("gares.index")->with('message', 'Gare updated successfully');
    }


    public function delete(Gare $gare)
    {
        $gare->delete();

        return redirect()->route("gares.index")->with(['message' => 'Gare moved to the recycle bin']);
    }

    public function restaure($id)
    {
        $gare = Gare::withTrashed()->findOrFail($id);

        $gare->restore();

        return redirect()->route("gares.index")->with(['message' => 'Gare restored successfully']);
    }
}
