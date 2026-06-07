<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     public function client()
    {
        return $this->hasOne(Client::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function sendEmailVerificationNotification()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Подтверждение email на сайте WOMEN')
                ->greeting('Здравствуйте!')
                ->line('Вы зарегистрировались на сайте студии маникюра WOMEN.')
                ->line('Для завершения регистрации подтвердите адрес электронной почты.')
                ->action('Подтвердить email', $url)
                ->line('Если вы не регистрировались на сайте, просто проигнорируйте это письмо.')
                ->salutation('С уважением, студия маникюра WOMEN');
        });
    
        $this->notify(new VerifyEmail);
    }
}
