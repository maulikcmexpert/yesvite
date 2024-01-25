<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGreeting extends Model
{
    protected $fillable = [
        'user_id',
        "template_name",
        "message",
        "message_sent_time",
        "custom_hours_after_event"
    ];
    use HasFactory;
}
