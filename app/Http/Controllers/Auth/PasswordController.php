<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Met à jour le mot de passe de l'utilisateur.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->load("infoEmploye")->update([
            'password' => Hash::make($validated['password']), 
        ]);

        // Redirige l'utilisateur avec un message de succès
        return back()->with('status', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
