<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPost, EventPostPollOption};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPostPoll extends Model
{

    use HasFactory;

    public function event()
    {
        return $this->BelongsTo(Event::class);
    }

    public function event_post()
    {
        return $this->BelongsTo(EventPost::class);
    }

    public function event_posts()
    {
        return $this->BelongsTo(EventPost::class);
    }

    public function event_poll_option()
    {
        return $this->hasMany(EventPostPollOption::class);
    }

    public function user_poll_data()
    {
        return $this->hasMany(UserEventPollData::class);
    }
}
