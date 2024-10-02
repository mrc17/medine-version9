<?php

namespace App\Models;


use App\Models\TrajetHeureDepart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeureDepart extends Model
{
    use HasFactory;

    protected $fillable = ['heure'];

    // Relation avec la table HeureDepart
    public function trajetHeuresDepart()
    {
        return $this->hasMany(TrajetHeureDepart::class, 'trajet_heure_departs');
    }
}
