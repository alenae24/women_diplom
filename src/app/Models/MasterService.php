<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterService extends Model
{
    protected $table = 'master_services';

    protected $fillable = [
        'master_id',
        'service_id',
        'price',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
