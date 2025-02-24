<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventPostPollOption, EventPostPoll, User};

class UserEventPollData extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_post_poll_id',
        'event_poll_option_id',
        'user_id'

    ];
    public function event_post_poll()
    {
        return $this->belongsTo(EventPostPoll::class);
    }


    public function event_post_poll_option()
    {
        return $this->belongsTo(EventPostPollOption::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
