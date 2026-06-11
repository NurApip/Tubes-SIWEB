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
        ];
    }

    public function isActiveMember(): bool
    {
        return (int) $this->is_member === 1;
    }

    public function isPendingMember(): bool
    {
        return (int) $this->is_member === 2;
    }

    public function membershipLabel(): string
    {
        return match ((int) $this->is_member) {
            1 => 'Member Aktif',
            2 => 'Menunggu Verifikasi',
            default => 'Regular Customer',
        };
    }
}
