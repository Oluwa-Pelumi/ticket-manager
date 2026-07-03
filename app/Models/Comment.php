<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Reply or note attached to a support ticket.
 */
class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'images',
        'user_id',
        'content',
        'ticket_id',
    ];

    /**
     * Attribute type casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
    ];

    /** The ticket this comment belongs to. */
    public function ticket()
    {
        return $this->belongsTo(
            Ticket::class, 
            'ticket_id', 
            'id'
        );
    }

    /** The user who wrote this comment. */
    public function user()
    {
        return $this->belongsTo(
            User::class, 
            'user_id', 
            'id'
        );
    }
}
