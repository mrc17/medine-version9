<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\InfoEmploye;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoEmployeController extends Controller
{
    public function index()
    {
        $employes = InfoEmploye::with("user.role")->get();
        $users = User::with("role")->whereIn("role_id", [4, 5, 7])->get();

        return Inertia::render('Employe/Index', [
            'auth' => auth()->user(),
            'employes' => $employes,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'post' => 'required|string|max:255',
            'path' => 'nullable|string|max:255',
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'adresse' => 'required|string|max:255',
            'carteIdentite' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'required|exists:users,id|unique:info_employes,user_id',
        ]);

        try {
            if ($request->hasFile('carteIdentite')) {
                $profilePicturePath = $request->file('carteIdentite')->storeAs(
                    'profile_pictures',
                    Str::random(10) . '.' . $request->file('carteIdentite')->extension(),
                    'public'
                );
                $validated['path'] = $profilePicturePath;
            }

            $employe = InfoEmploye::create([
                'login' => $validated['login'],
                'password' => bcrypt($validated['password']),
                'post' => $validated['post'],
                'path' => $validated['path'],
                'adresse' => $validated['adresse'],
                'user_id' => $validated['user_id'],
            ]);

            return redirect()->route('employees.index')->with(['success' => 'Employé créé avec succès', 'data' => $employe]);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with(['error' => 'Erreur lors de la création de l\'employé', 'error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, InfoEmploye $employe)
    {
        $validated = $request->validate([
            'login' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'post' => 'sometimes|required|string|max:255',
            'path' => 'nullable|string|max:255',
            'adresse' => 'sometimes|required|string|max:255',
            'carteIdentite' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        try {
            if ($request->hasFile('carteIdentite')) {
                // Delete old picture if it exists
                if ($employe->path) {
                    Storage::disk('public')->delete($employe->path);
                }
                $profilePicturePath = $request->file('carteIdentite')->storeAs(
                    'profile_pictures',
                    Str::random(10) . '.' . $request->file('carteIdentite')->extension(),
                    'public'
                );
                $validated['path'] = $profilePicturePath;
            }

            $employe->update([
                'login' => $validated['login'] ?? $employe->login,
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : $employe->password,
                'post' => $validated['post'] ?? $employe->post,
                'path' => $validated['path'] ?? $employe->path,
                'adresse' => $validated['adresse'] ?? $employe->adresse,
                'user_id' => $validated['user_id'] ?? $employe->user_id,
            ]);

            return redirect()->route('employees.index')->with(['success' => 'Employé mis à jour avec succès', 'data' => $employe]);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with(['error' => 'Erreur lors de la mise à jour de l\'employé', 'error' => $e->getMessage()], 500);
        }
    }

    public function resetPassword(InfoEmploye $employe)
    {
        try {
            $temporaryPassword = Str::random(12);
            $employe->password = bcrypt($temporaryPassword);
            $employe->save();

            return redirect()->route('employees.index')->with([
                'message' => 'Mot de passe réinitialisé avec succès',
                'temporaryPassword' => $temporaryPassword,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with(['message' => 'Erreur lors de la réinitialisation du mot de passe', 'error' => $e->getMessage()], 500);
        }
    }
}
