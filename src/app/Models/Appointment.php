<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'master_id',
        'service_id',
        'start_time',
        'end_time',
        'price',
        'status',      
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
