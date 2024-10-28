<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gare;
use Inertia\Inertia;
use GuzzleHttp\Client;
use App\Models\Employe;
use App\Models\Compagnie;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class CompagnieController extends Controller
{
    /**
     * Display a listing of validated companies.
     */
    public function index()
    {
        // Fetch companies with their responsible person where the company is validated
        $compagnies = Compagnie::with('responsable')->where('valide', 1)->get();

        return Inertia::render('Compagnie/Index', [
            'auth' => auth()->user(),
            'compagnies' => $compagnies,
        ]);
    }

    public function count(){
        $demandeCount = Compagnie::with('responsable')->where('valide', 0)->count();

        return response()->json(['count' => $demandeCount]);
    }

    /**
     * Display a listing of pending company validation requests.
     */
    public function demandes()
    {
        // Fetch unvalidated companies ordered by newest first
        $compagnies = Compagnie::with('responsable')->where('valide', 0)->orderBy('id', 'desc')->get();

        return Inertia::render('Compagnie/Wattend', [
            'auth' => auth()->user(),
            'compagnies' => $compagnies,
        ]);
    }

    /**
     * Display detailed information for a specific company.
     *
     * @param  Compagnie $compagnie
     * @return \Inertia\Response
     */
    public function show(Compagnie $compagnie)
    {
        $compagnie = Compagnie::with([
            'responsable',
            'gares',
            'cars',
            'portefeuille',
            'tickets.user',
            'employes.user.gares_caisse.gare',
            'employes.user.role',
            'employes.user.gare_comptable',
            'employes.user.gare_responsable',
        ])->findOrFail($compagnie->id);

        return Inertia::render('Compagnie/Show', [
            'auth' => auth()->user(),
            'compagnie' => $compagnie,
        ]);
    }



    /**
     * Validate the company and process the company logo image.
     *
     * @param  Compagnie $compagnie
     * @return \Illuminate\Http\RedirectResponse
     */
    public function valide(Compagnie $compagnie)
    {
        // Update the company to set it as validated and process its image
        $updatedData = [
            'valide' => 1,
            'image' => $this->removeBackground($compagnie->path),
        ];

        $compagnie->update($updatedData);

        return redirect()->route('compagnies.index')->with('success', 'Compagnie validée avec succès.');
    }

    /**
     * Remove the background from the company's image using the remove.bg API.
     *
     * @param  string $filePath
     * @return string
     */
    public function removeBackground($filePath)
    {
        $client = new Client();
        $apiKey = env('REMOVE_BG_API_KEY');

        $image = fopen(public_path($filePath), 'r');

        try {
            // Make the request to remove.bg to process the image
            $response = $client->request('POST', 'https://api.remove.bg/v1.0/removebg', [
                'headers' => [
                    'X-Api-Key' => $apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => $image,
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto',
                    ],
                ],
                'sink' => public_path('images/compagnies/processed_' . basename($filePath)),
            ]);

            fclose($image);

            if ($response->getStatusCode() === 200) {
                // Return the path of the processed image if the request was successful
                return 'images/compagnies/processed_' . basename($filePath);
            }
        } catch (RequestException $e) {
            // Log the error message for debugging purposes
            Log::error('Remove.bg error: ' . $e->getMessage());
        }

        // Return the original file path if the request fails
        return $filePath;
    }

    /**
     * Remove the specified company and its associated data (employees, stations, etc.)
     *
     * @param  Compagnie $compagnie
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Compagnie $compagnie)
    {
        // Delete the responsible user's account
        User::where('id', $compagnie->responsable_id)->delete();

        // Delete all related employees and stations (gares)
        Employe::where('compagnie_id', $compagnie->id)->delete();
        Gare::where('compagnie_id', $compagnie->id)->delete();

        // Finally, delete the company itself
        $compagnie->forceDelete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Compagnie supprimée avec succès.');
    }

    public function delete(Compagnie $compagnie)
    {
        $compagnie->delete();
        return redirect()->route('compagnies.index')->with('message', 'Compagnie supprimée définitivement');
    }
}
