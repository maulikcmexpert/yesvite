<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use App\Models\{User, Event, EventPostPhoto, EventPhotoCommentReaction};



class EventPostPhotoComment extends Model

{

    use HasFactory;



    public function user()

    {

        return $this->belongsTo(User::class);
    }

    public function event()

    {

        return $this->belongsTo(Event::class);
    }



    public function event_post_photo()

    {

        return $this->belongsTo(EventPostPhoto::class);
    }



    public function replies()

    {

        return $this->hasMany(EventPostPhotoComment::class, 'parent_comment_id');
    }



    public function parentComment()

    {

        return $this->belongsTo(EventPostPhotoComment::class, 'parent_comment_id');
    }



    public function post_photo_comment_reaction()

    {

        return $this->hasMany(EventPhotoCommentReaction::class, 'event_photo_comment_id', 'id');
    }


    public function getMainParentId($commentId)
    {
        return EventPostPhotoComment::with('parentComment')
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
