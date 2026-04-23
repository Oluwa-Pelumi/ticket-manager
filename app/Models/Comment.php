<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'images',
        'user_id',
        'content',
        'ticket_id',
    ];

    /**
     * 
     */
    protected $casts = [
        'images' => 'array',
    ];

    /**
     * 
     */
    public function ticket()
    {
        return $this->belongsTo(
            Ticket::class, 
            'ticket_id', 
            'id'
        );
    }

    /**
     * 
     */
    public function user()
    {
        return $this->belongsTo(
            User::class, 
            'user_id', 
            'id'
        );
    }
}
