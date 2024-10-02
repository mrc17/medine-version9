<?php

namespace App\Http\Controllers\Api;

use App\Models\Gare;
use App\Models\User;
use App\Models\Employe;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\UpdateRoleRequest;
use App\Http\Requests\Api\CreateEmployeeRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;

class EmployeController extends Controller
{

    public function compagnieemployees()
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Récupérer les employés de la compagnie
        $employes = Employe::where('compagnie_id', $compagnie->id)->get();

        // Extraire les IDs des utilisateurs associés aux employés
        $employeIds = $employes->pluck('user_id')->toArray();

        // Récupérer les utilisateurs correspondant à ces employés
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
            // Trouver la gare associée à l'utilisateur, si elle existe
            $gare = Gare::where('responsable_gare_id', $user->id)
                ->orWhere('comptable_id', $user->id)
                ->first();
            return [
                'value' => $user->id,
                'label' => $user->nom . " " . $user->prenom,
                'post' => $user->role->nom,
                "numero" => $user->telephone,
                'gare' => $gare ? [
                    'id' => $gare->id,
                    'nom' => $gare->nom,
                ] : null, // Return null if no gare is found
            ];
        });
    }

    public function profilUser($employeId)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Trouver l'utilisateur
        $employee = Employe::where(['user_id' => $employeId, "compagnie_id" => $compagnie->id])->first();


        // Vérifier si l'utilisateur existe
        if (!$employee) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'L\'employé demandé n\'existe pas.',
            ], 404);
        }
        // Trouver la gare associée à l'utilisateur, si elle existe
        $gare = Gare::where('responsable_gare_id', $employee->user_id)
            ->orWhere('comptable_id', $employee->user_id)
            ->first();


        return response()->json([
            'success' => true,
            'status_code' => 200,
            'employe' => [
                'value' => $employee->id,
                'label' => $employee->user->nom . ' ' . $employee->user->prenom,
                'post' => $employee->user->role->nom,
                'numero' => $employee->user->telephone,
                'gare' => $gare ? [
                    'id' => $gare->id,
                    'nom' => $gare->nom,
                ] : null, // Return null if no gare is found
            ],
        ]);
    }



    public function store(CreateEmployeeRequest $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }


        // Préparer les données de l'utilisateur
        $data = $request->except(['password_confirmation']);
        $data['password'] = Hash::make($request->input('password'));

        // Créer l'utilisateur
        $user = User::create($data);

        // Créer l'employé
        Employe::create([
            'user_id' => $user->id,
            'compagnie_id' => $compagnie->id,
        ]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Employé créé avec succès.',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Trouver l'employé
        $employe = Employe::where('user_id', $request->input('employe_id'))->first();
        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ], 404);
        }

        $gare = Gare::where("responsable_gare_id", $request->input('employe_id'))->orWhere('comptable_id', $request->input('employe_id'))->first();

        if ($gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Veillez attribuer le post à un autre employé avant suppression.',
            ]);
        }
        // Supprimer l'employé
        $employe->delete();

        // Supprimer l'utilisateur associé
        $user = User::find($request->input('employe_id'));
        if ($user) {
            $user->delete();
        }

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Employé supprimé avec succès.',
        ], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        // Ensure the user is an admin
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Retrieve the user's company and validate existence and permissions
        $compagnie = $user->compagnie;
        if (!$compagnie || $user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Find the user to update
        $userToUpdate = User::find($request->input("user_id"));
        if (!$userToUpdate) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        // Check if the user is an employee
        $employeExists = Employe::where("user_id", $userToUpdate->id)->exists();
        if (!$employeExists) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Utilisateur n\'est votre employé.',
            ], 404);
        }

        if (!Hash::check($request->input('password_old'), $userToUpdate->password)) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'mot de passe incorrecte',
            ], 404);
        }

        // Update the password
        $userToUpdate->password = Hash::make($request->input('password'));
        $userToUpdate->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Mot de passe modifié avec succès.',
        ], 200);
    }

    public function updateRole(Request $request, $id)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }


        // Trouver l'utilisateur
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        // Valider les données de la requête
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Mettre à jour le rôle
        $user->role_id = $request->input('role_id');
        $user->save();

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => 'Rôle modifié avec succès.',
        ], 200);
    }

    public function change(UpdateRoleRequest $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ]);
        }

        $employe = User::find($request->input('user_id'));

        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ]);
        }


        // Check if the role_id is valid
        $validRoles = [2, 3]; // Example valid role IDs
        if (!in_array($request->input('role_id'), $validRoles)) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Permission refusée. Le rôle spécifié est invalide.',
            ], 403);
        }

        $employe->update(["role_id" => $request->input('role_id')]);

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => "L'employé a desomais " . $employe->role->nom . " avec succès.",
        ]);
    }

    public function assignToStation(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Trouver la gare et l'employé
        $gare = Gare::find($request->input('gare_id'));
        if (!$gare) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Gare non trouvée.',
            ], 404);
        }

        $employe = User::find($request->input('user_id'));

        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ], 404);
        }

        if ($gare->where("responsable_gare_id", $employe->id)->OrWhere("comptable_id", $employe->id)->exists()) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé ne peut pas avoir deux poste',
            ], 404);
        }

        // Mettre à jour la gare avec le responsable ou le comptable
        if ($employe->role_id == 2) {
            $gare->update([
                'responsable_gare_id' => $employe->id,
            ]);
            $role = 'responsable';
        } else {
            $gare->update([
                'comptable_id' => $employe->id,
            ]);
            $role = 'comptable';
        }

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'message' => "{$employe->nom} {$employe->prenom} est désormais le {$role} de la gare.",
        ], 200);
    }

    public function suspendre(Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur est un administrateur (role_id 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Vérifier si la compagnie existe
        $compagnie = Compagnie::find($user->compagnie->id);
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Vérifier si l'utilisateur est le responsable de la compagnie
        if ($user->id !== $compagnie->responsable_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'êtes pas autorisé à accéder à cette compagnie.',
            ], 403);
        }

        // Rechercher l'utilisateur par employe_id
        $employe_user = User::find($request->input('employe_id'));
        if (!$employe_user) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé.',
            ], 404);
        }

        // Rechercher l'employé correspondant
        $employe = Employe::where('user_id', $employe_user->id)->first();
        if (!$employe) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Employé non trouvé dans les données de l\'employé.',
            ], 404);
        }

        // Alterner l'état valide de l'employé
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
}
