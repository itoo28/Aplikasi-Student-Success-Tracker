<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
        'jenjang',
        'is_active',
        'registration_code',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
