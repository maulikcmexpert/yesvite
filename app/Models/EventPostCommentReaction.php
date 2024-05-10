<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventPostComment;
use App\Models\User;

class EventPostCommentReaction extends Model
{
    use HasFactory;

    public function event_post_comment()
    {
        return  $this->belongsTo(EventPostComment::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
}
