<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Academic or organizational programme model.
 */
class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Users enrolled under this programme.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
