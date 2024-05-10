<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, User, EventPost};

class UserReportToPost extends Model
{
    use HasFactory;

    public function events()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event_posts()
    {
        return $this->belongsTo(EventPost::class, 'event_post_id');
    }
}
