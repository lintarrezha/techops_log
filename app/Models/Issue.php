<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'activity_log_id',
        'judul_masalah',
        'deskripsi_masalah',
        'solusi',
        'kategori',
        'status',
        'resolved_at'
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function activityLog()
    {
        return $this->belongsTo(ActivityLog::class);
    }
}
