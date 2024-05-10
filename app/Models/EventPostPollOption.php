<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventPostPoll, UserEventPollData};

class EventPostPollOption extends Model
{
    use HasFactory;

    public function event_poll()
    {
        return $this->BelongsTo(EventPostPoll::class);
    }

    public function poll_option()
    {
        return $this->hasMany(UserEventPollData::class);
    }
}
