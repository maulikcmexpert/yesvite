<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventInvitedUser, EventImage, EventSchedule, InviteViewRate, EventPost, EventPostImage, EventPostPoll, EventUserStory, EventPostComment, EventPostReaction, Notification, EventPotluckCategory, EventPotluckCategoryItem, PostControl, EventSetting, UserReportToPost};

use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    protected $fillable = [
        'event_type_id',
        'event_type_id',
        'event_name',
        'user_id',
        'hosted_by',
        'start_date',
        'end_date',
        'latitude',
        'longitude',
        'rsvp_by_date_set',
        'rsvp_by_date',
        'rsvp_start_time',
        'rsvp_start_timezone',
        'rsvp_end_time_set',
        'rsvp_end_time',
        'rsvp_end_timezone',
        'event_location_name',
        'address_1',
        'address_2',
        'state',
        'zip_code',
        'city',
        'message_to_guests',
        'greeting_card_id',
        'gift_registry_id',
        'is_draft_save'
    ];
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function event_image()
    {
        return $this->hasMany(EventImage::class);
    }

    public function event_schedule()
    {
        return $this->hasMany(EventSchedule::class);
    }


    public function event_invited_user()
    {
        return $this->hasMany(EventInvitedUser::class);
    }

    public function invite_view_rate()
    {
        return $this->hasMany(InviteViewRate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event_post()
    {
        return $this->hasMany(EventPost::class);
    }

    public function post_image()
    {
        return $this->hasMany(EventPostImage::class);
    }

    public function event_post_poll()
    {

        return $this->hasMany(EventPostPoll::class);
    }

    public function event_user_story()
    {
        return $this->hasMany(EventUserStory::class);
    }

    public function event_post_comment()
    {
        return $this->hasMany(EventPostComment::class);
    }

    public function event_post_reaction()
    {
        return $this->hasMany(EventPostReaction::class);
    }

    public function event_post_photo()
    {
        return $this->hasMany(EventPostPhoto::class);
    }

    public function event_post_photo_comment()
    {
        return $this->hasMany(EventPostPhotoComment::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
    public function event_potluck_category()
    {
        return $this->hasMany(EventPotluckCategory::class);
    }
    public function event_potluck_category_item()
    {
        return $this->hasMany(EventPotluckCategoryItem::class);
    }
    public function post_control()
    {

        return $this->hasMany(PostControl::class);
    }


    public function event_settings()
    {

        return $this->hasOne(EventSetting::class);
    }
    public function user_report_to_posts()
    {

        return $this->hasMany(UserReportToPost::class, 'event_id', 'id');
    }

    public function user_potluck_items()
    {

        return $this->hasMany(UserPotluckItem::class, 'event_id', 'id');
    }
}
