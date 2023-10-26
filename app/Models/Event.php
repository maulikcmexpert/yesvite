<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventInvitedUser, EventImage, EventSchedule};


class Event extends Model
{
    protected $fillable = [
        'event_type_id',
        'event_type_id',
        'event_name',
        'user_id',
        'hosted_by',
        'start_date',
        'end_date',
        'rsvp_by_date',
        'rsvp_start_time',
        'rsvp_start_timezone',
        'rsvp_end_time_set',
        'rsvp_end_time',
        'rsvp_end_timezone',
        'event_location_name',
        'address_1',
        'address_2',
        'state',
        'zip_code',
        'city',
        'message_to_guests',
    ];
    use HasFactory;


    public function event_image()
    {
        return $this->hasMany(EventImage::class);
    }

    public function event_schedule()
    {
        return $this->hasMany(EventSchedule::class);
    }

    public function event_co_host()
    {
        return $this->hasMany(EventCoHost::class);
    }

    public function event_invited_user()
    {
        return $this->hasMany(EventInvitedUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
