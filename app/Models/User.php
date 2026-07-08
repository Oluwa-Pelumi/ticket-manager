<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Authenticated user with role-based access (admin, support, user).
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'whatsapp_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /** Check whether the user has the admin role. */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Check whether the user has the support role. */
    public function isSupport(): bool
    {
        return $this->role === 'support';
    }

    /** Tickets submitted by this user. */
    public function tickets() {
        return $this->hasMany(
            Ticket::class,
            'user_id',
            'id'
        );
    }

    /** Tickets assigned to this user for support handling. */
    public function assignedTickets() {
        return $this->hasMany(
            Ticket::class,
            'attended_to_by',
            'id'
        );
    }

    /** Accessor to load tickets assigned to this support user from JSON column. */
    public function getAssignedTicketsAttribute() {
        return Ticket::whereJsonContains('attended_to_by', $this->id)->get();
    }

}
