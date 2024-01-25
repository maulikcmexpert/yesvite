<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\{Event, EventPotluckCategory};

class EventPotluckCategoryItem extends Model
{
    protected $fillable = [
        'event_id',
        'event_potluck_category_id',
        'description',
        'quantity'
    ];
    use HasFactory;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function event_potluck_category()
    {
        return $this->belongsTo(EventPotluckCategory::class);
    }
}
