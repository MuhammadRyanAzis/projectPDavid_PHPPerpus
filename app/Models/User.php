<?php

namespace App\Models;

use App\Concerns\HasTeams;
use Database\Factories\UserFactory;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'email',
    'password',
    'current_team_id',
    'role'
])]
#[Hidden([
    'password',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'remember_token'
])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,
        HasTeams,
        Notifiable,
        PasskeyAuthenticatable,
        TwoFactorAuthenticatable;

    /**
     * User memiliki satu profile anggota.
     */
    public function anggota(): HasOne
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * User dapat menjadi petugas untuk banyak peminjaman.
     */
    public function peminjamanSebagaiPetugas(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }

    /**
     * User dapat menjadi petugas untuk banyak pengembalian.
     */
    public function pengembalianSebagaiPetugas(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'petugas_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}