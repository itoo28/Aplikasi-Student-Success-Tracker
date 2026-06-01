<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GuidanceLog extends Model
{
    protected $fillable = [
        'user_id',
        'lecturer_id',
        'semester',
        'guidance_date',
        'topic',
        'document_path',
        'activity_photo_path',
        'notes',
        'resolution',
        'status',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'guidance_date' => 'date',
            'is_completed' => 'boolean',
        ];
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        if (! $this->student || ! $this->student->phone_number) {
            return null;
        }

        $phone = preg_replace('/\D+/', '', $this->student->phone_number);
        $text = "Halo {$this->student->name}, jadwal bimbingan Anda telah dibuat untuk tanggal {$this->guidance_date->format('d M Y')} dengan topik: {$this->topic}. Mohon konfirmasi kehadiran Anda.";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
    }

    public function getActivityPhotoUrlAttribute(): ?string
    {
        return $this->activity_photo_path ? Storage::url($this->activity_photo_path) : null;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
