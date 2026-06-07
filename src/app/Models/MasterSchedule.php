<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSchedule extends Model
{
    protected $table = 'master_schedules';

    protected $fillable = [
        'master_id',
        'date',
        'start_time',
        'end_time',
        'is_day_off',
    ];

    protected $casts = [
        'date' => 'date',
        'is_day_off' => 'boolean',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }
}
