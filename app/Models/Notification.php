<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, User, EventPost};

class Notification extends Model
{
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function post()
    {
        return $this->belongsTo(EventPost::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender_user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
