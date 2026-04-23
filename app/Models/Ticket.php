<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasUuids;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'user_id',
        'subject',
        'content',
        'filename',
        'images',
        'priority',
        'attended_to_by',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
    ];

    public function comments() {
        return $this->hasMany(
            Comment::class, 
            'ticket_id', 
            'id'
        )->with('user')->latest();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function user() {
        return $this->belongsTo(
            User::class, 
            'user_id', 
            'id'
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function attendant() {
        return $this->belongsTo(
            User::class, 
            'attended_to_by', 
            'id'
        );
    }
}
