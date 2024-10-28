<?php

namespace App\Models;

use App\Models\Compagnie;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Affiliation extends Model
{
    use HasFactory, SoftDeletes;

    // Specify the attributes that can be mass assigned
    protected $fillable = [
        'user_id',
        'compagnie_id',
        'taux',
        "delete_at"
    ];

    /**
     * Get the user associated with the affiliation.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company associated with the affiliation.
     */
    public function compagnie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Compagnie::class);
    }

    /**
     * Set the rules for validating attributes.
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->taux < 0 || $model->taux > 0.5) {
                throw new \InvalidArgumentException('Le taux doit être compris entre 0 et 0.5');
            }
        });
    }
}
