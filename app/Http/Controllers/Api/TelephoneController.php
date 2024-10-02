<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Compagnie;
use App\Models\Telephone;
use App\Models\Portefeuille;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\TelephoneRequest;
use App\Http\Requests\Api\TelephoneUpdateRequest;
use App\Http\Requests\Api\VerifieTelephoneRequest;
use App\Http\Requests\Api\UserUpdatePasswordRequest;

class TelephoneController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(TelephoneRequest $request)
    {
        try {
            $telephoneNumber = $request->input('telephone');

            $recentCode = Telephone::where('numero', $telephoneNumber)
                ->where('created_at', '>', Carbon::now()->subMinutes(5))
                ->first();

            if ($recentCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un code a déjà été envoyé récemment. Veuillez attendre 5 minutes.',
                ], 429);
            }

            $code = $this->genereCodeToSms();
            $telephone = $this->sendCodeToSmsAndSaveDb($telephoneNumber, $code);

            return response()->json([
                'success' => true,
                'message' => 'Verification réussie, un code a été envoyé par SMS.',
                'telephone_id' => $telephone->id,
                'code' => $code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Méthode pour générer un code aléatoire de 6 chiffres
    private function genereCodeToSms()
    {
        //return rand(10000, 99999); // Plage de 6 chiffres
        return 76322; // Plage de 6 chiffres
    }

    // Méthode pour envoyer le code par SMS et l'enregistrer dans la base de données
    private function sendCodeToSmsAndSaveDb($telephone, $code)
    {
        // Envoi du code par SMS (la logique dépendra du fournisseur de SMS)
        // Exemple : SmsService::send($telephone, "Votre code est : $code");

        // Enregistrement du code dans la table 'telephones'
        $telephoneVerification = Telephone::create([
            'numero' => $telephone,
            'code' => bcrypt($code),
        ]);

        return $telephoneVerification;
    }

    public function update(TelephoneUpdateRequest $request)
    {
        $validated = $request->validated();

        // Trouver le téléphone
        $telephone = Telephone::find($validated['telephone_id']);

        if (!$telephone) {
            return response()->json([
                'success' => false,
                'message' => 'Téléphone non trouvé.',
            ], 404);
        }



        // Vérifier le code fourni
        if (!Hash::check($request->input('code'), $telephone->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Code invalide.',
            ], 400);
        }

        // Mise à jour du téléphone
        $telephone->update(['etat' => 1]);


        $existe = User::where([
            'telephone' => $telephone->numero,
            "role_id" => 5,
        ])->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Votre numero de téléphone est dèjà utilisé.',
            ], 403);
        }

        // Préparer les données pour la création de l'utilisateur
        $data = [
            "nom" => $request->input("nom"),
            "telephone" => $telephone->numero,
            "prenom" => $request->input("prenom"),
            "password" => $request->input("password"),
        ];

        // Créer l'utilisateur
        $user = $this->createUser($data);

        Auth::login($user);
        // Créer un token d'accès personnel pour l'utilisateur
        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'status_code' => 201,
            'message' => 'utilisateur créée avec succès',
            'utilisateur' => [
                'token' => $token,
                "nom" => $user->nom,
                "user_id" => $user->id,
                "prenom" => $user->prenom,
                'role' => $user->role->nom,
                "telephone" => $user->telephone
            ]
        ], 201);
    }

    private function createUser($data)
    {
        try {
            return User::create([
                'etat' => 1,
                'role_id' => 5,
                'nom' => $data["nom"],
                'prenom' => $data["prenom"],
                "etat" => 1,
                'telephone' => $data["telephone"],
                'password' => bcrypt($data["password"]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(TelephoneRequest $request)
    {
        // Recherche de l'utilisateur par téléphone et rôle
        $user = User::where('telephone', $request->input('telephone'))
            ->whereIn('role_id', [5, 6])
            ->first();

        // Vérification si l'utilisateur existe
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        // Réponse en cas de succès
        return response()->json([
            'success' => true,
            'message' => 'Compte retrouvé.',
            'telephone' => $user->telephone,
        ]);
    }

    public function confirmationpassword(TelephoneRequest $request)
    {
        // Récupération des informations de l'utilisateur
        $user = User::where([
            'telephone' => $request->input('telephone'),
            'role_id' => $request->input('role_id')
        ])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé ou rôle incorrect.',
            ], 404);
        }

        $telephoneNumber = $request->input('telephone');

        // Vérifie si un code a été envoyé récemment
        $recentCode = Telephone::where('numero', $telephoneNumber)
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->first();

        if ($recentCode) {
            return response()->json([
                'success' => false,
                'message' => 'Un code a déjà été envoyé récemment. Veuillez attendre 5 minutes.',
            ], 429); // Code de statut 429 pour Too Many Requests
        }

        // Génère un nouveau code
        $code = $this->genereCodeToSms();

        // Envoie le code par SMS et l'enregistre dans la base de données
        $telephone = $this->sendCodeToSmsAndSaveDb($telephoneNumber, $code);

        return response()->json([
            'success' => true,
            'message' => 'Vérification réussie, un code a été envoyé par SMS.',
            'telephone_id' => $telephone->id,
            // Il est préférable de ne pas retourner le code en production pour des raisons de sécurité
            'code' => $code, // Optionnel
        ]);
    }

    public function updatepassword(UserUpdatePasswordRequest $request)
    {
        // Validation des données
        $validated = $request->validated();

        // Trouver le téléphone par ID
        $telephone = Telephone::find($validated['telephone_id']);

        if (!$telephone) {
            return response()->json([
                'success' => false,
                'message' => 'Téléphone non trouvé.',
            ], 404);
        }

        // Vérification du code (ici, j'assume que le code est stocké en clair, mais il est préférable de le hasher)
        if (!Hash::check($request->input('code'), $telephone->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Code invalide.',
            ], 400);
        }

        // Mettre à jour l'état du téléphone
        $telephone->update(['etat' => 1]);

        // Recherche de l'utilisateur par numéro de téléphone
        $user = User::where('telephone', $telephone->numero)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Votre compte est introuvable.',
            ], 403);
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        // Retourner une réponse de succès
        return response()->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès.',
        ], 200);
    }

    public function coderetrait()
    {

        $user = Auth::user();

        // Vérifiez si l'utilisateur a le rôle approprié (ici 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Obtenir la compagnie liée à l'utilisateur
        $compagnie = Compagnie::where('responsable_id', $user->id)->first();

        // Vérifiez si la compagnie existe
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Obtenir le portefeuille lié à la compagnie
        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        // Vérifiez si le portefeuille existe
        if (!$portefeuille) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Le portefeuille n\'a pas été trouvé.',
            ], 404);
        }


        // Vérifie si un code a été envoyé récemment
        $recentCode = Telephone::where('numero', $portefeuille->numero_depot)
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->first();

        if ($recentCode) {
            return response()->json([
                'success' => false,
                'message' => 'Un code a déjà été envoyé récemment. Veuillez attendre 5 minutes.',
            ], 429); // Code de statut 429 pour Too Many Requests
        }

        // Génère un nouveau code
        $code = $this->genereCodeToSms();

        // Envoie le code par SMS et l'enregistre dans la base de données
        $telephone = $this->sendCodeToSmsAndSaveDb($portefeuille->numero_depot, $code);

        return response()->json([
            'success' => true,
            'message' => 'Vérification réussie, un code a été envoyé par SMS.',
            'telephone_id' => $telephone->id,
            // Il est préférable de ne pas retourner le code en production pour des raisons de sécurité
            'code' => $code, // Optionnel
        ]);
    }

    public function retrait(VerifieTelephoneRequest $request)
    {
        $user = Auth::user();

        // Vérifiez si l'utilisateur a le rôle approprié (ici 1)
        if ($user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'message' => 'Vous n\'avez pas le droit d\'accéder à cette action.',
            ], 403);
        }

        // Obtenir la compagnie liée à l'utilisateur
        $compagnie = Compagnie::where('responsable_id', $user->id)->first();

        // Vérifiez si la compagnie existe
        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'La compagnie n\'a pas été trouvée.',
            ], 404);
        }

        // Trouver le téléphone
        $telephone = Telephone::find($request->input('telephone_id'));

        if (!$telephone) {
            return response()->json([
                'success' => false,
                'message' => 'Téléphone non trouvé.',
            ], 404);
        }

        // Vérifier le code fourni
        if (!Hash::check($request->input('code'), $request->input("code"))) {
            return response()->json([
                'success' => false,
                'message' => 'Code invalide.',
            ], 400);
        }

        // Mise à jour du téléphone
        $telephone->update(['etat' => 1]);

        // Obtenir le portefeuille lié à la compagnie
        $portefeuille = Portefeuille::where('compagnie_id', $compagnie->id)->first();

        // Vérifiez si le portefeuille existe
        if (!$portefeuille) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Le portefeuille n\'a pas été trouvé.',
            ], 404);
        }

        return response()->json([
            'success' => false,
            'status_code' => 404,
            'message' => 'Le retaire confirmée.',
        ], 404);
    }
}
