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

    public function getDosenWhatsappLinkAttribute(): ?string
    {
        if (! $this->dosen || ! $this->dosen->phone_number) {
            return null;
        }

        $phone = $this->dosen->whatsappPhoneNumber();
        if (! $phone) {
            return null;
        }

        $text = "Halo Bapak/Ibu {$this->dosen->name}, saya {$this->mahasiswa->name} ({$this->mahasiswa->identifier}) telah mengajukan bimbingan akademik untuk tanggal {$this->tanggal->format('d M Y')} dengan topik: \"{$this->topik}\". Mohon kesediaan Bapak/Ibu untuk memeriksa dan memvalidasi pengajuan tersebut. Terima kasih.";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
    }

    public function getWhatsappCancelLinkAttribute(): ?string
    {
        if (! $this->mahasiswa || ! $this->mahasiswa->phone_number) {
            return null;
        }

        $phone = $this->mahasiswa->whatsappPhoneNumber();
        if (! $phone) {
            return null;
        }

        $text = "Bimbingan dibatalkan. Silakan menghubungi dosen pembimbing untuk penjadwalan ulang. Terima kasih.";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
    }

    public function getWhatsappValidationLinkAttribute(): ?string
    {
        if (! $this->mahasiswa || ! $this->mahasiswa->phone_number) {
            return null;
        }

        $phone = $this->mahasiswa->whatsappPhoneNumber();
        if (! $phone) {
            return null;
        }

        $statusLabel = $this->status === 'validated' ? 'DISETUJUI' : 'DITOLAK';
        $tanggalFormatted = $this->tanggal->format('d M Y');
        
        $text = "Halo {$this->mahasiswa->name}, pengajuan bimbingan akademik Anda pada tanggal {$tanggalFormatted} dengan topik: \"{$this->topik}\" telah {$statusLabel}.";
        if ($this->catatan && $this->catatan !== '-') {
            $text .= " Catatan dosen: \"{$this->catatan}\".";
        }
        $text .= " Terima kasih.";

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
