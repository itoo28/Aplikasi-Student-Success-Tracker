<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkkmSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'verified_at' => 'datetime',
        'kaprodi_verified_at' => 'datetime',
        'kemahasiswaan_verified_at' => 'datetime',
        'is_progress_counted' => 'boolean',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function kaprodiVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kaprodi_verified_by');
    }

    public function kemahasiswaanVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kemahasiswaan_verified_by');
    }

    public function pointRule(): BelongsTo
    {
        return $this->belongsTo(PointRule::class, 'point_rule_id');
    }

    public function scopeFinalApproved(Builder $query): Builder
    {
        return $query->where('status_verifikasi', 'disetujui');
    }
}
