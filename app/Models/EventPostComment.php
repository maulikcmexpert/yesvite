<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPost, User};

class EventPostComment extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function event_post()
    {
        return $this->belongsTo(EventPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(EventPostComment::class, 'parent_comment_id', 'id');
    }

    public function parentComment()
    {
        return $this->belongsTo(EventPostComment::class, 'parent_comment_id');
    }

    public function post_comment_reaction()
    {
        return $this->hasMany(EventPostCommentReaction::class);
    }

    public function getMainParentId($commentId)
    {
        return EventPostComment::with('parentComment')
            ->where('id', $commentId)
            ->first()
            ->getMainParentIdAttribute();
    }

    // Accessor to retrieve the main parent ID
    public function getMainParentIdAttribute()
    {
        $parentComment = $this->parentComment;

        // Keep traversing up until the main parent is found
        while ($parentComment && $parentComment->parentComment) {
            $parentComment = $parentComment->parentComment;
        }

        return $parentComment ? $parentComment->id : $this->id;
    }
}
