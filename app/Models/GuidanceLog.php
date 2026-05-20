<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class GuidanceLog extends Model
{
    protected $fillable = [
        'user_id',
        'lecturer_id',
        'semester',
        'guidance_date',
        'topic',
        'document_path',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'guidance_date' => 'date',
        ];
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
