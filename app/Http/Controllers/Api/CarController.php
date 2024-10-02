<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Gare;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CreateCarRequest;
use App\Models\Employe;

class CarController extends Controller
{

    public function compagniecars()
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = $user->compagnie;

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        $cars = $compagnie->cars; // Assurez-vous que la relation est définie dans le modèle Compagnie

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'cars' => $this->formaData($cars),
        ], 200);
    }

    public function formaData($cars)
    {
        return $cars->map(function ($car) {
            return [
                'value' => $car->id,
                'label' => $car->imatriculation,
                'place' => $car->place,
                "valide" => $car->valide
            ];
        });
    }

    public function carCompagnieTogares()
    {
        $user = Auth::user();


        // Vérifier si l'utilisateur est un administrateur (role_id 2)
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

        $cars = $gare->compagnie->cars; // Assurez-vous que la relation est définie dans le modèle Compagnie

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'cars' => $this->formaDataCar($cars),
        ], 200);
    }

    public function formaDataCar($cars)
    {
        return $cars->map(function ($car) {
            return [
                'value' => $car->id,
                'label' => $car->imatriculation,
                'place' => $car->place,
                "valide" => $car->valide
            ];
        });
    }

    /**
     * Stocker une nouvelle voiture dans la base de données.
     */
    public function store(CreateCarRequest $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = $user->compagnie;

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $carsData = $request->validated()['cars'];
        $createdCars = [];

        foreach ($carsData as $carData) {
            $createdCars[] = Car::create(
                [
                    'imatriculation' => $carData['imatriculation'],
                    'place' => $carData['place'],
                    'compagnie_id' => $compagnie->id
                ]
            );
        }

        return response()->json([
            'success' => true,
            'status_code' => 201,
            'message' => 'Les voitures ont été créées avec succès.',
            'cars' => $createdCars,
        ], 201);
    }


    /**
     * Réparer une voiture spécifique.
     */
    public function etat($car_id)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = $user->compagnie;

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $car = Car::find($car_id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La voiture n\'a pas été trouvée.',
            ], 404);
        }

        $car->valide = !$car->valide;
        $car->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => $car->valide ? 'La voiture a été réparée avec succès.' : 'La voiture a été mise au garage avec succès.',
        ], 200);
    }


    /**
     * Supprimer la voiture spécifiée de la base de données.
     */
    public function destroy($car_id)
    {
        $user = Auth::user();

        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $compagnie = $user->compagnie;

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        $car = Car::find($car_id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La voiture n\'a pas été trouvée.',
            ], 404);
        }

        $car->delete();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'La voiture a été supprimée avec succès.',
        ], 200);
    }
}
