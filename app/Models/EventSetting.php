<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    protected $fillable = [
        'event_id',
        'allow_for_1_more',
        'allow_limit',
        'adult_only_party',
        'rsvp_by_date_status',
        'thank_you_cards',
        'add_co_host',
        'gift_registry',
        'events_schedule',
        'event_wall',
        'guest_list_visible_to_guests',
        'podluck',
        'rsvp_updates',
        'event_updates',
        'send_event_dater_reminders',
        'rsvp_reminders_once_a_week'
    ];
    use HasFactory;
}
