<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventUserStory};


class UserEventStory extends Model
{

    protected $fillable = [
        'event_story_id',
        'story',
        'duration',
        'type'
    ];
    use HasFactory;

    public function event_user_story()
    {
        return $this->BelongsTo(EventUserStory::class, 'event_story_id', 'id');
    }


    public function user_seen_story()
    {
        return $this->hasMany(UserSeenStory::class, 'user_event_story_id', 'id');
    }
}
