<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfoEmploye extends Model
{
    use HasFactory;

    protected $table = 'info_employes';

    // Colonnes qui peuvent être massivement assignées
    protected $fillable = [
        'login',
        'password',
        'post',
        'path',
        'adresse',
        'user_id',
    ];

    // Relation avec le modèle User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
