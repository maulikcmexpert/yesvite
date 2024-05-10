<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, User, UserEventStory};

class EventUserStory extends Model
{

    protected $fillable = [
        'event_id',
        'user_id',
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

    public function user_event_story()
    {
        return $this->hasMany(UserEventStory::class, 'event_story_id', 'id');
    }
}
