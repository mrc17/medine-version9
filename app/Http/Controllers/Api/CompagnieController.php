<?php

namespace App\Http\Controllers\Api;

use App\Models\Compagnie;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class CompagnieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $compagnies = Compagnie::where("valide", 1)->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'compagnies' => $this->formaData($compagnies),
        ], 200);
    }

    public function formaData($compagnies)
    {
        return $compagnies->map(function ($compagnie) {
            return [
                'id' => $compagnie->id,
                "nom" => $compagnie->nom,
                "sig" => $compagnie->sig,
                "contact" => $compagnie->contact,
                "localite" => $compagnie->localite,
                "image" => $this->getFileContent($compagnie->image), // Lire le contenu du fichier
            ];
        });
    }

    /**
     * Lire le contenu du fichier image.
     *
     * @param string $filePath
     * @return string
     */
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
