<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, User, EventPost, EventPostPhoto};

class EventInvitedUser extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'rsvp_status',
        'adults',
        'kids',
        'message_to_host',
        'message_by_video',
        'read',
        'rsvp_d',
        'prefer_by',
        'is_co_host',

    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event_post_photo()
    {
        return $this->belongsTo(EventPostPhoto::class, 'user_id', 'user_id');
    }
}
