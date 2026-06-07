<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'base_price',
        'description',
        'image'
    ];

    public function masterPrices()
    {
        return $this->hasMany(MasterService::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
