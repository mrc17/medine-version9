<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Gare;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Employe;
use App\Models\Compagnie;
use App\Models\GareCaisse;
use App\Models\Affiliation;
use App\Models\InfoEmploye;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'password',
        'role_id',
        'etat',
        "attempt_logins",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    *The function get role user
    *
    * @param string
    */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function compagnie()
    {
        return $this->hasOne(Compagnie::class, 'responsable_id');
    }

    public function gares_responsable()
    {
        return $this->hasMany(Gare::class, 'responsable_gare_id');
    }
    public function gares_caisse()
    {
        return $this->hasOne(GareCaisse::class, 'user_id');
    }

    public function gares_comptable()
    {
        return $this->hasMany(Gare::class, 'comptable_id');
    }

    public function gare_responsable()
    {
        return $this->hasOne(Gare::class, 'responsable_gare_id');
    }

    public function gare_comptable()
    {
        return $this->hasOne(Gare::class, 'comptable_id');
    }


    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function affiliations()
    {
        return $this->hasMany(Affiliation::class);
    }

    public function infoEmploye()
    {
        return $this->hasOne(InfoEmploye::class, 'user_id');
    }
}
