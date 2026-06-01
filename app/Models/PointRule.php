<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'poin' => 'integer',
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(SkkmSubmission::class);
    }
}
