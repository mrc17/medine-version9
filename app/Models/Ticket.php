<?php

namespace App\Models;

use App\Models\Gare;
use App\Models\User;
use App\Models\Compagnie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        "place",
        "depart",
        "arrivee",
        "heure_depart",
        "codeticket",
        "tarif",
        "codedepart",
        "mode_paiement",
        "numero_paiement",
        "date_reservation",
        "montant_ttc",
        "reference",
        "status",
        "user_id",
        "gare_id",
        "compagnie_id",
        "num_ticket"
    ];

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }
}
