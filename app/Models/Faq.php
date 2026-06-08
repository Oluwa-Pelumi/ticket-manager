<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Frequently asked question displayed on the public site.
 */
class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'order'];
}
