<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, User, EventPostImage, EventPostPoll, EventPostComment, EventPostReaction, Notification, EventInvitedUser, PostControl, UserReportToPost};

class EventPost extends Model
{

    use HasFactory;


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function post_image()
    {
        return $this->hasMany(EventPostImage::class);
    }

    public function event_post_poll()
    {

        return $this->hasOne(EventPostPoll::class);
    }

    public function event_post_comment()
    {

        return $this->hasMany(EventPostComment::class);
    }

    public function event_post_reaction()
    {

        return $this->hasMany(EventPostReaction::class);
    }

    public function notification()
    {

        return $this->hasMany(Notification::class);
    }

    public function post_control()
    {

        return $this->hasMany(PostControl::class);
    }

    public function user_report_to_posts()
    {

        return $this->hasMany(UserReportToPost::class, 'event_post_id', 'id');
    }
}
