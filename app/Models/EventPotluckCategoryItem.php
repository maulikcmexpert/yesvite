<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPotluckCategoryItem extends Model
{
    protected $fillable = [
        'event_id',
        'event_potluck_category_id',
        'description',
        'quantity'
    ];
    use HasFactory;
}
