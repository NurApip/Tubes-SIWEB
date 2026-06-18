<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'email',
        'password',
        'hp',
        'no_hp',
        'foto',
        'role',
        'is_member',
        'membership_bukti',
        'membership_requested_at',
        'membership_started_at',
        'membership_expires_at',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_member' => 'integer',
            'membership_requested_at' => 'datetime',
            'membership_started_at' => 'datetime',
            'membership_expires_at' => 'datetime',
        ];
    }

    public function isActiveMember(): bool
    {
        return (int) $this->is_member === 1
            && $this->membership_expires_at !== null
            && $this->membership_expires_at->isFuture();
    }

    public function isPendingMember(): bool
    {
        return (int) $this->is_member === 2;
    }

    public function isExpiredMember(): bool
    {
        return (int) $this->is_member === 1
            && $this->membership_expires_at !== null
            && $this->membership_expires_at->isPast();
    }

    public function membershipLabel(): string
    {
        if ($this->isActiveMember()) {
            return 'Member Aktif';
        }

        if ($this->isPendingMember()) {
            return 'Menunggu Verifikasi';
        }

        if ($this->isExpiredMember()) {
            return 'Membership Kedaluwarsa';
        }

        return 'Regular Customer';
    }

    public function favoriteLapangans()
    {
        return $this->belongsToMany(
            Lapangan::class,
            'favorite_lapangans',
            'user_id',
            'lapangan_id'
        )->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
