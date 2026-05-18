<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'skkm_role',
        'identifier',
        'semester',
        'jenjang_studi',
        'lecturer_id',
        'program_studi_id',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'semester' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(self::class, 'lecturer_id');
    }

    public function adviseeStudents(): HasMany
    {
        return $this->hasMany(self::class, 'lecturer_id');
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function skkmSubmissions(): HasMany
    {
        return $this->hasMany(SkkmSubmission::class, 'mahasiswa_id');
    }

    public function verifiedSkkmSubmissions(): HasMany
    {
        return $this->hasMany(SkkmSubmission::class, 'verified_by');
    }

    public function skkmProgress(): HasOne
    {
        return $this->hasOne(SkkmProgress::class, 'mahasiswa_id');
    }

    public function guidanceLogs(): HasMany
    {
        return $this->hasMany(GuidanceLog::class);
    }

    public function lecturerGuidanceLogs(): HasMany
    {
        return $this->hasMany(GuidanceLog::class, 'lecturer_id');
    }

    public function hasSkkmRole(string ...$roles): bool
    {
        $normalizedRoles = array_map(function (string $role): string {
            return $role === 'student' ? 'mahasiswa' : $role;
        }, $roles);

        return in_array($this->resolvedSkkmRole(), $normalizedRoles, true);
    }

    public function resolvedSkkmRole(): string
    {
        $storedRole = $this->skkm_role;

        if ($storedRole === null || $storedRole === '') {
            return $this->role === 'lecturer' ? 'dosen_pa' : 'mahasiswa';
        }

        return $storedRole === 'student' ? 'mahasiswa' : $storedRole;
    }
}
