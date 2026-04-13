<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistAnswer extends Model
{
    protected $fillable = ['activity_log_id', 'template_id', 'jawaban'];

    public function template()
    {
        return $this->belongsTo(ChecklistTemplate::class, 'template_id');
    }
}
