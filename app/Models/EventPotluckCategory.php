<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Event, EventPotluckCategoryItem};

class EventPotluckCategory extends Model
{
    protected $fillable = [
        'event_id',
        'category',
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
}
