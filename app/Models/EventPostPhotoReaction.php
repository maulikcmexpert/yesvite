<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User, EventPostPhoto};

class EventPostPhotoReaction extends Model
{
    use HasFactory;

    public function event_post_photo()
    {
        return  $this->belongsTo(EventPostPhoto::class);
    }
    public function user()
    {
        return  $this->belongsTo(User::class);
    }
}
