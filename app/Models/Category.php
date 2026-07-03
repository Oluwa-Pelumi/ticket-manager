<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ticket category.
 */
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the tickets for the category.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
