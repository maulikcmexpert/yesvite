<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;
use App\Models\{Device, Event, EventInvitedUser, EventPostComment, InviteViewRate, EventPost, EventUserStory, EventPostReaction, UserEventPollData, EventAddContact, EventPostPhotoReaction, EventPostPhotoComment, Notification, UserReportToPost};

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'country_code',
        'phone_number',
        'company_name',
        'password',
        'account_type',
        'remember_token',
        'user_parent_id',
        'app_user',
        'is_user_phone_contact',
        'parent_user_phone_contact',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function invited_event()
    {
        return $this->hasMany(EventInvitedUser::class);
    }



    public function invite_view_rate()
    {
        return $this->hasMany(InviteViewRate::class);
    }

    // event which create user //
    public function event()
    {
        return $this->hasMany(Event::class);
    }

    public function event_post()
    {
        return $this->hasMany(EventPost::class);
    }

    public function event_user_story()
    {
        return $this->hasMany(EventUserStory::class);
    }

    public function event_post_reaction()
    {
        return $this->hasMany(EventPostReaction::class);
    }
    public function event_post_comment()
    {
        return $this->hasMany(EventPostComment::class);
    }
    public function user_event_poll_data()
    {
        return $this->hasMany(UserEventPollData::class);
    }

    public function contact_list()
    {
        return $this->hasMany(EventAddContact::class);
    }
    public function event_post_photo_reaction()
    {
        return $this->hasMany(EventPostPhotoReaction::class);
    }

    public function event_post_photo_comment()
    {
        return $this->hasMany(EventPostPhotoComment::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function sender_id()
    {
        return $this->hasMany(Notification::class, 'sender_id', 'id');
    }

    public function user_report_to_posts()
    {

        return $this->hasMany(UserReportToPost::class, 'user_id', 'id');
    }
}
