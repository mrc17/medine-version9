<?php

namespace App\Models;

use App\Models\User;
use App\Models\Compagnie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'compagnie_id',
        'valide',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compagnie()
    {
        return $this->belongsTo(Compagnie::class);
    }
}
