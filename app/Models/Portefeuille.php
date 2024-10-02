<?php

namespace App\Models;

use App\Models\Compagnie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portefeuille extends Model
{
    use HasFactory;
    protected $fillable = [
        'commission',
        'montant_ticket',
        'compagnie_id',
        "password",
        "numero_depot",
        "attempt_logins",
    ];

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }
}
