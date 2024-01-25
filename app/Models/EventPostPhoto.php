<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use App\Models\{EventPostPhotoReaction, Event, User, EventPostPhotoData, EventInvitedUser};



class EventPostPhoto extends Model

{



    protected $fillable = [

        'event_id',

        'user_id',

        'post_message'

    ];

    use HasFactory;



    public function event()

    {

        return $this->belongsTo(Event::class);
    }

    public function scopeWithInvitedUsers($query)
    {
        return $query->with(['event.event_invited_user' => function ($query) {
            $query->whereColumn('event_invited_user.user_id', 'event_post_photo.user_id');
        }]);
    }



    public function event_post_Photo_comment()

    {

        return $this->hasMany(EventPostPhotoComment::class);
    }

    public function event_post_photo_reaction()

    {

        return $this->hasMany(EventPostPhotoReaction::class);
    }



    public function user()

    {

        return $this->belongsTo(User::class);
    }

    public function event_post_photo_data()

    {

        return $this->hasMany(EventPostPhotoData::class);
    }
}
