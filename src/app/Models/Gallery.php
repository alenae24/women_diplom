<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['master_id', 'service_id', 'image', 'title'];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}