<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    UserEventStory,
    User
};

class UserSeenStory extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_event_story()
    {
        return $this->belongsTo(UserEventStory::class);
    }
}
