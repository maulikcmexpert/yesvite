<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventPostPhoto;

class EventPostPhotoData extends Model
{
    protected $fillable = [
        'event_post_photo_id',
        'post_media',
        'type'
    ];
    use HasFactory;

    public function event_post_photo()
    {
        return $this->belongsTo(EventPostPhoto::class);
    }
}
