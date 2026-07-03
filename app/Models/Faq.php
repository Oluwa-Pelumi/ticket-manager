<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Frequently asked question displayed on the public site.
 */
class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'order'];
}
