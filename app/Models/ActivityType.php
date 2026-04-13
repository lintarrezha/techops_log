<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $fillable = ['nama_kegiatan', 'deskripsi', 'icon', 'is_active'];

    public function checklistTemplates()
    {
        return $this->hasMany(ChecklistTemplate::class)->orderBy('urutan');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
