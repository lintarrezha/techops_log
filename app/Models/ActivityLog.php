<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type_id',
        'tanggal_kegiatan',
        'status_kegiatan',
        'catatan',
        'custom_sections'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'datetime',
        'custom_sections'  => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
    public function answers()
    {
        return $this->hasMany(ChecklistAnswer::class);
    }
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
