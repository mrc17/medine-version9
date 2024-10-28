<?php

namespace App\Models;

use App\Models\Gare;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GareCaisse extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "gare_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }
}
