<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPost};

class EventPostImage extends Model
{

    protected $fillable = [
        'event_id',
        'event_post_id',
        'post_image',
        'duration',
        'type'
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }


    public function event_post()
    {
        return $this->belongsTo(EventPost::class);
    }

    public function user_report_to_post()
    {
        return $this->belongsTo(UserReportToPost::class);
    }
}
