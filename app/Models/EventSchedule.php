<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event};

class EventSchedule extends Model
{
    protected $fillable = [
        'event_id',
        'activity_title',
        'start_time',
        'event_date',
        'end_time',
        'type'
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
