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
    protected $appends = ['hashid', 'attendant', 'attendants', 'has_support_replied'];

    /** Check if any support/admin staff has replied to this ticket. */
    public function getHasSupportRepliedAttribute()
    {
        if ($this->relationLoaded('comments')) {
            return $this->comments->contains(function ($comment) {
                return $comment->user && in_array($comment->user->role, ['admin', 'support']) && $comment->user_id !== $this->user_id;
            });
        }
        return $this->comments()->whereHas('user', function ($q) {
            $q->whereIn('role', ['admin', 'support']);
        })->where('user_id', '!=', $this->user_id)->exists();
    }

    /** Encode the ticket ID as a public-facing hashid. */
    public function getHashidAttribute()
    {
        return \Vinkla\Hashids\Facades\Hashids::encode($this->id);
    }

    /** Get the last (most recent) support staff member assigned to this ticket. */
    public function getAttendantAttribute()
    {
        $ids = $this->attended_to_by ?? [];
        if (empty($ids)) {
            return null;
        }
        $lastId = end($ids);
        return User::find($lastId);
    }

    /** Get all support staff members assigned to this ticket. */
    public function getAttendantsAttribute()
    {
        $ids = $this->attended_to_by ?? [];
        if (empty($ids)) {
            return collect();
        }
        // Return in the order they were assigned
        $users = User::whereIn('id', $ids)->get()->keyBy('id');
        return collect($ids)->map(fn($id) => $users->get($id))->filter();
    }

    /** Add a support staff member to the ticket's attendants list. */
    public function addAttendant($userId)
    {
        if (!$userId) return;
        $ids = $this->attended_to_by ?? [];
        if (!in_array((int)$userId, $ids, true)) {
            $ids[] = (int)$userId;
            $this->update(['attended_to_by' => $ids]);
        }
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
        'status',
        'attachments',
        'user_id',
        'subject',
        'content',
        'filename',
        'priority',
        'category_id',
        'attended_to_by',
    ];

    /**
     * Attribute type casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments'            => 'array',
        'attended_to_by'    => 'array',
    ];

    /** Ensure attended_to_by is stored as a JSON array of integers. */
    public function setAttendedToByAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['attended_to_by'] = null;
        } elseif (is_numeric($value)) {
            $this->attributes['attended_to_by'] = json_encode([(int) $value]);
        } elseif (is_array($value)) {
            $this->attributes['attended_to_by'] = json_encode(array_values(array_unique(array_map('intval', $value))));
        } else {
            $this->attributes['attended_to_by'] = $value;
        }
    }

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

    /** The category this ticket belongs to. */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
