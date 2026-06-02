<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal',
        'semester',
        'topik',
        'catatan',
        'tipe_pengajuan',
        'status',
        'document_path',
        'resolution',
        'activity_photo_path',
        'group_key',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'semester' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function getWhatsappLinkAttribute(): ?string
    {
        if (! $this->mahasiswa || ! $this->mahasiswa->phone_number) {
            return null;
        }

        $phone = $this->mahasiswa->whatsappPhoneNumber();
        if (! $phone) {
            return null;
        }

        $text = "Halo {$this->mahasiswa->name}, jadwal bimbingan Anda telah dibuat untuk tanggal {$this->tanggal->format('d M Y')} dengan topik: {$this->topik}. Mohon konfirmasi kehadiran Anda.";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
    }

    public function getTipePengajuanLabelAttribute(): string
    {
        return match ($this->tipe_pengajuan) {
            'undangan_dosen' => 'Undangan Dosen',
            'mandiri_mahasiswa' => 'Pengajuan Mahasiswa',
            default => ucfirst(str_replace('_', ' ', $this->tipe_pengajuan)),
        };
    }
}
