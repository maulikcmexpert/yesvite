<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\{Event, User};

class EventCoHost extends Model
{
    protected $fillable = [
        'event_id', // Add this line if it's not already present
        'user_id'
    ];
    use HasFactory;

    public function event()
    {
        return $this->BelongsTo(Event::class);
    }

    public function user()
    {
        return $this->BelongsTo(User::class);
    }
}
