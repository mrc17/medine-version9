<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\User;
use App\Models\Employe;
use App\Models\GareCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\CreateEmployeeRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;

class ResponsableEmployeeController extends Controller
{
    public function get()
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

        // Récupérer les employés de la gare
        $caisseGares = GareCaisse::where('gare_id', $gare->id)->get();
        $employeIds = $caisseGares->pluck('user_id')->toArray();
        $users = User::whereIn('id', $employeIds)->get();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'users' => $this->formaData($users),
        ], 200);
    }

    public function formaData($users)
    {
        return $users->map(function ($user) {
            return [
                'value' => $user->id,
                'label' => $user->nom . " " . $user->prenom,
                'post' => $user->role->nom,
                "numero" => $user->telephone,
                "etat" => $user->etat,
            ];
        });
    }

    public function store(CreateEmployeeRequest $request)
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

        DB::beginTransaction();

        try {
            $data = $request->except(['password_confirmation']);
            $data['password'] = Hash::make($request->input('password'));

            $newUser = User::create($data);

            Employe::create([
                'user_id' => $newUser->id,
                'compagnie_id' => $gare->compagnie->id,
            ]);

            GareCaisse::create([
                'user_id' => $newUser->id,
                'gare_id' => $gare->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Employé créé avec succès.',
                'user' => $newUser,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Une erreur s\'est produite lors de la création de l\'employé.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function updatePassword(UpdatePasswordRequest $request)
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

        $userToUpdate = User::find($request->input("user_id"));

        if (!$userToUpdate) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        $employeExists = Employe::where("user_id", $userToUpdate->id)->exists();
        if (!$employeExists) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Utilisateur n\'est pas votre employé.',
            ], 404);
        }

        if (!Hash::check($request->input('password_old'), $userToUpdate->password)) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Mot de passe incorrect.',
            ], 404);
        }

        $userToUpdate->password = Hash::make($request->input('password'));
        $userToUpdate->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Mot de passe modifié avec succès.',
        ], 200);
    }

    public function suspend(Request $request)
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

        $employe_user = User::find($request->input('employe_id'));
        if (!$employe_user) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ], 404);
        }

        $employe = Employe::where('user_id', $employe_user->id)->first();
        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé dans les données de l\'employé.',
            ], 404);
        }

        $etat = $employe->valide ? 0 : 1;
        $employe->update([
            'valide' => $etat
        ]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => $etat ? 'L\'employé a été activé avec succès.' : 'L\'employé a été suspendu avec succès.',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        $employe_user = User::find($request->input('employe_id'));
        if (!$employe_user) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ], 404);
        }

        $employe = Employe::where('user_id', $employe_user->id)->first();
        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé dans les données de l\'employé.',
            ], 404);
        }

        $employe->delete();
        $employe_user->delete();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Employé supprimé avec succès.',
        ], 200);
    }
}
