<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPost, User};

class EventPostComment extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function event_post()
    {
        return $this->belongsTo(EventPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(EventPostComment::class, 'parent_comment_id');
    }

    public function parentComment()
    {
        return $this->belongsTo(EventPostComment::class, 'parent_comment_id');
    }

    public function post_comment_reaction()
    {
        return $this->hasMany(EventPostCommentReaction::class);
    }
}
