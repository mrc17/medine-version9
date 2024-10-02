<?php

namespace App\Models;

use App\Models\Car;
use App\Models\Gare;
use App\Models\Ticket;
use App\Models\Employe;
use App\Models\Portefeuille;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compagnie extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "sig",
        "valide",
        "contact",
        "localite",
        "image",
        "responsable_id",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function gares()
    {
        return $this->hasMany(Gare::class);
    }
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

    public function portefeuilles()
    {
        return $this->hasMany(Portefeuille::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
