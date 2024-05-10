<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPotluckCategoryItem, UserPotluckItem};

class EventPotluckCategory extends Model
{
    protected $fillable = [
        'event_id',
        'category',
        'user_id',
        'quantity'
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function event_potluck_category_item()
    {
        return $this->hasMany(EventPotluckCategoryItem::class);
    }

    public function user_potluck_items()
    {
        return $this->hasMany(UserPotluckItem::class, 'event_potluck_category_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
