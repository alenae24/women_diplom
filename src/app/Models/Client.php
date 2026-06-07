<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
     protected $fillable = [
        'user_id',
        'birthdate',
        'notes',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, User::class);
    }

}
