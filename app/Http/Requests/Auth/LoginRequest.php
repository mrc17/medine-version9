<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Str;
use App\Models\InfoEmploye;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return true; // Tous les utilisateurs peuvent faire une demande de connexion.
    }

    /**
     * Récupère les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Tente d'authentifier les informations d'identification de la requête.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Vérifie si le nombre de tentatives n'est pas dépassé
        $this->ensureIsNotRateLimited();

        // Définit la durée de blocage en minutes et convertit en secondes
        $decayMinutes = 10;
        $decaySeconds = $decayMinutes * 60;

        // Vérifie si le login existe dans la base de données
        $infoEmploye = InfoEmploye::where('login', $this->input('login'))->with('user')->first();

        // Si le login n'existe pas, incrémente les tentatives et lance une exception de validation
        if (!$infoEmploye) {
            RateLimiter::hit($this->throttleKey(), $decaySeconds);

            throw ValidationException::withMessages([
                'login' => 'Ces informations d\'identification ne correspondent pas à nos enregistrements.',
            ]);
        }

        // Récupère l'utilisateur associé à l'employé
        $user = $infoEmploye->user;

        // Récupère le mot de passe soumis
        $password = $this->input('password');

        // Vérifie si le mot de passe fourni correspond à celui de la base de données
        $isCorrect = Hash::check($password, $infoEmploye->password);

        // Si le mot de passe est incorrect, incrémente le compteur de tentatives et lance une exception
        if (!$isCorrect) {
            $user->increment("attempt_logins"); // Incrémente le compteur de tentatives de connexion
            RateLimiter::hit($this->throttleKey(), $decaySeconds);

            throw ValidationException::withMessages([
                'login' => 'Ces informations d\'identification ne correspondent pas à nos enregistrements.',
            ]);
        }

        // Vérifie si le nombre de tentatives de connexion a atteint la limite
        if ($user->attempt_logins >= 5) {
            RateLimiter::hit($this->throttleKey(), $decaySeconds);

            throw ValidationException::withMessages([
                'message' => 'Cet compte est bloqué.',
            ]);
        }

        // Vérifie si l'utilisateur est verrouillé
        if ($user->etat == 1) {
            RateLimiter::hit($this->throttleKey(), $decaySeconds);

            throw ValidationException::withMessages([
                'message' => 'Cet compte est verrouillé, veuillez contacter votre responsable supérieur.',
            ]);
        }

        // Vérifie si l'utilisateur a un rôle valide (4 ou 7)
        if ($user->role_id != 4 && $user->role_id != 7) {
            $user->increment("attempt_logins"); // Incrémente le compteur de tentatives de connexion
            RateLimiter::hit($this->throttleKey(), $decaySeconds);

            throw ValidationException::withMessages([
                'login' => 'Cet utilisateur n\'a pas les permissions requises.',
            ]);
        }

        // Effectue l'authentification de l'utilisateur
        Auth::login($user, $this->boolean('remember'));

        // Si l'authentification réussit, efface les tentatives précédentes
        RateLimiter::clear($this->throttleKey());
    }



    /**
     * Vérifie que la requête de connexion n'est pas limitée par un trop grand nombre de tentatives.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $maxAttempts = 4;

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Récupère les messages d'erreur personnalisés pour la validation.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "login" => "Authentication a échoué", // Message d'erreur pour le champ de connexion.
            "password" => "Authentication a échoué", // Message d'erreur pour le mot de passe.
        ];
    }

    /**
     * Récupère la clé de limitation des tentatives pour la requête.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        // Génère une clé unique pour le suivi des tentatives de connexion,
        // en utilisant le champ de login et l'adresse IP de l'utilisateur.
        return Str::transliterate(Str::lower($this->string('login')) . '|' . $this->ip());
    }
}
