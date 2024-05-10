<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{Event, EventPotluckCategory, UserPotluckItem};

class EventPotluckCategoryItem extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'self_bring_item',
        'event_potluck_category_id',
        'description',
        'quantity'
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event_potluck_category()
    {
        return $this->belongsTo(EventPotluckCategory::class);
    }

    public function user_potluck_items()
    {
        return $this->hasMany(UserPotluckItem::class, 'event_potluck_item_id', 'id');
    }
}
