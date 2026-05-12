<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'identifier',
        'semester',
        'lecturer_id',
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

    public function skkmSubmissions(): HasMany
    {
        return $this->hasMany(SkkmSubmission::class, 'mahasiswa_id');
    }

    public function verifiedSkkmSubmissions(): HasMany
    {
        return $this->hasMany(SkkmSubmission::class, 'verified_by');
    }

    public function skkmProgress()
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
}
