<?php

namespace App\Models;

use App\Models\Trajet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModeDepart extends Model
{
    use HasFactory;

    protected $fillable = ['mode'];

    // Relation avec la table Trajet
    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}
