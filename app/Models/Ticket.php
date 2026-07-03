<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Support ticket with hashid-based public references and optional recurring orders.
 */
class Ticket extends Model
{
    use HasFactory;
    protected $appends = ['hashid'];

    /** Encode the ticket ID as a public-facing hashid. */
    public function getHashidAttribute()
    {
        return \Vinkla\Hashids\Facades\Hashids::encode($this->id);
    }

    /** Resolve route model binding from hashid or raw ID. */
    public function resolveRouteBinding($value, $field = null)
    {
        // Try decoding as a hashid
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($value);
        if (!empty($decoded)) {
            return $this->where('id', $decoded[0])->firstOrFail();
        }

        // Fallback in case the raw integer ID was passed
        return $this->where($field ?? 'id', $value)->firstOrFail();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'images',
        'user_id',
        'subject',
        'content',
        'filename',
        'priority',
        'order_type',
        'category_id',
        'attended_to_by',
        'whatsapp_number',
        'recurrence_period',
        'order_activations',
        'custom_recurrence_date',
    ];

    /**
     * Attribute type casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images'            => 'array',
        'order_activations' => 'array',
    ];

    /** Comments on this ticket, newest first, with author loaded. */
    public function comments() {
        return $this->hasMany(
            Comment::class,
            'ticket_id',
            'id'
        )->with('user')->latest();
    }

    /** The user who submitted this ticket. */
    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    /** The support staff member assigned to this ticket. */
    public function attendant() {
        return $this->belongsTo(
            User::class,
            'attended_to_by',
            'id'
        );
    }

    /** The category this ticket belongs to. */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
