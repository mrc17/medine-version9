<?php

namespace App\Models;

use App\Models\Gare;
use App\Models\ModeDepart;
use App\Models\HeureDepart;
use App\Models\Planification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trajet extends Model
{
    use HasFactory;


    protected $fillable = ['depart', 'arrivee', 'prix', 'gare_id', 'mode_depart_id'];

    // Relation avec la table Gare
    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }

    // Relation avec la table ModeDepart
    public function modeDepart()
    {
        return $this->belongsTo(ModeDepart::class);
    }

    // Relation avec la table HeureDepart
    public function trajetHeuresDepart()
    {
        return $this->belongsToMany(HeureDepart::class, 'trajet_heure_departs');
    }

    // Relation avec Planification
    public function planifications()
    {
        return $this->hasMany(Planification::class);
    }
}
