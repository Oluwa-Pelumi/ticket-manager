<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ticket category grouped for organization and filtering.
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'group',
    ];

    /**
     * Get the tickets for the category.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
