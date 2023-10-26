<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;
use App\Models\{Device, Event, EventInvitedUser, EventCoHost};

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'company_name',
        'password',
        'account_type',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function invited_event()
    {
        return $this->hasMany(EventInvitedUser::class);
    }

    public function event_co_host()
    {
        return $this->hasMany(EventCoHost::class);
    }

    // event which create user //
    public function event()
    {
        return $this->hasMany(Event::class);
    }
}
