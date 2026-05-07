<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SkkmPoint extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'category',
        'points',
        'document_path',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
