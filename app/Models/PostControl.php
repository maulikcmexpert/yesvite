<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Event,
    EventPost
};

class PostControl extends Model
{
    use HasFactory;

    public function events()
    {
        return $this->belongsTo(Event::class);
    }

    public function event_posts()
    {
        return $this->belongsTo(EventPost::class);
    }
}
