<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{

     protected $fillable = [
        'name',
        'level',       
        'specialization',
        'bio',
        'photo',
    ];

    const SPECIALIZATIONS = [
        'manicure' => 'Маникюр',
        'pedicure' => 'Педикюр',
        'universal' => 'Универсал',
    ];

    public function getSpecializationNameAttribute()
    {
        return self::SPECIALIZATIONS[$this->specialization] ?? $this->specialization;
    }

    public function getLevelNameAttribute()
    {
        return match($this->level) {
            'junior' => 'Мастер',
            'middle' => 'Топ-мастер',
            'top' => 'Топ-про мастер',
            default => $this->level,
        };
    }

    public function servicePrices()
    {
        return $this->hasMany(MasterService::class);
    }

    public function schedules()
    {
        return $this->hasMany(MasterSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getPriceForService($serviceId)
    {
        $price = $this->servicePrices()->where('service_id', $serviceId)->value('price');
        
        if ($price !== null) {
            return $price;
        }
        
        
        $service = Service::find($serviceId);
        return $service ? $service->base_price : null;
    }

}
