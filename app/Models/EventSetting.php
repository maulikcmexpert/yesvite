<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventSetting extends Model
{
    protected $fillable = [
        'event_id',
        'allow_for_1_more',
        'allow_limit',
        'adult_only_party',

        'thank_you_cards',
        'add_co_host',
        'gift_registry',
        'events_schedule',
        'event_wall',
        'guest_list_visible_to_guests',
        'podluck',
        'rsvp_updates',
        'event_wall_post',
        'send_event_dater_reminders',
        'request_event_photos_from_guests'
    ];
    use HasFactory;

    public function events()
    {
        return $this->belongsTo(Event::class);
    }
}
