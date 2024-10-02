<?php

namespace App\Models;

use App\Models\Trajet;
use App\Models\HeureDepart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrajetHeureDepart extends Model
{
    use HasFactory;

    protected $fillable = ['trajet_id', 'heure_depart_id'];
    // Relation avec Trajet
    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    // Relation avec HeureDepart
    public function heureDepart()
    {
        return $this->belongsTo(HeureDepart::class);
    }
}
