<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    User,
    EventPotluckCategory,
    EventPotluckCategoryItem
};

class UserPotluckItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'event_id',
        'user_id',
        'event_potluck_category_id',
        'event_potluck_item_id',
        'quantity'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event_potluck_categories()
    {
        return $this->belongsTo(EventPotluckCategory::class, 'event_potluck_category_id');
    }

    public function event_potluck_category_items()
    {
        return $this->belongsTo(EventPotluckCategoryItem::class, 'event_potluck_item_id');
    }
}
