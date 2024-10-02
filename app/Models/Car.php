<?php

namespace App\Models;

use App\Models\Compagnie;
use App\Models\Planification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'imatriculation',
        'place',
        'compagnie_id',
    ];

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }

    // Relation avec Planification
    public function planifications()
    {
        return $this->hasMany(Planification::class);
    }
}
