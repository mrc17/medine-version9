<?php

namespace App\Models;

use App\Models\Car;
use App\Models\Gare;
use App\Models\Trajet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Planification extends Model
{
    use HasFactory;

    protected $fillable = ['car_id', 'gare_id', 'trajet_id', 'date','codedepart',"heure"];

    // Relation avec Car
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Relation avec Gare
    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }

    // Relation avec Trajet
    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }
}
