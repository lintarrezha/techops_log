<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    protected $fillable = [
        'activity_type_id',
        'section_label',
        'section_name',
        'pertanyaan',
        'tipe_input',
        'opsi_jawaban',
        'satuan',
        'is_required',
        'urutan'
    ];

    protected $casts = ['opsi_jawaban' => 'array'];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
}
