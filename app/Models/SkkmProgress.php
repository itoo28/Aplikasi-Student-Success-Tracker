<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkkmProgress extends Model
{
    use HasFactory;

    protected $table = 'skkm_progress';
    protected $primaryKey = 'mahasiswa_id';
    public $incrementing = false;

    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
