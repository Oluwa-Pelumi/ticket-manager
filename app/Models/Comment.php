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
        'user_id',
        'content',
        'ticket_id',
        'attachments',
    ];

    /**
     * Attribute type casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
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
