<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventPostPhotoComment;
use App\Models\User;

class EventPhotoCommentReaction extends Model
{
    use HasFactory;

    public function event_post_photo_comment()
    {
        $this->belongsTo(EventPostPhotoComment::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
