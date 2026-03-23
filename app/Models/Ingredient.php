<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function plats(): BelongsToMany
    {
        return $this->belongsToMany(Plat::class, 'ingredient_plate', 'ingredient_id', 'plate_id');
    }
}
