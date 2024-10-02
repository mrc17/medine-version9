<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\UserRequest;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated(); // Validate the request data

        // Only hash the password if it's provided
        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // Remove password if not provided
        }

        // Only hash the password if it's provided
        if (!isset($validatedData['telephone'])) {
            unset($validatedData['telephone']); // Remove password if not provided
        }

        // Update the user
        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $user = Auth::user();
        // Supprimer l'utilisateur
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès.',
        ]);
    }
}
