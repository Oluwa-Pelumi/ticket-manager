<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $appends = ['hashid'];

    public function getHashidAttribute()
    {
        return \Vinkla\Hashids\Facades\Hashids::encode($this->id);
    }

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
     * Undocumented variable
     *
     * @var array
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
     * Undocumented variable
     *
     * @var array
     */
    protected $casts = [
        'images'            => 'array',
        'order_activations' => 'array',
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

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
