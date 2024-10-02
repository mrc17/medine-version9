<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Trajet;
use App\Models\GareCaisse;
use App\Models\Planification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gare extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "ville",
        "commune",
        'compagnie_id',
        'caisse_id',
        'responsable_gare_id',
        'responsable_gare_id',
    ];


    public function responsable_gare()
    {
        return $this->belongsTo(User::class, 'responsable_gare_id');
    }

    public function comptable()
    {
        return $this->belongsTo(User::class, 'comptable_id');
    }

    public function caisse()
    {
        return $this->hasMany(GareCaisse::class, 'gare_id');
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    // Relation avec Planification
    public function planifications()
    {
        return $this->hasMany(Planification::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
