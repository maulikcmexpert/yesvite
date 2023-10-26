<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPotluckCategory extends Model
{
    protected $fillable = [
        'event_id',
        'category',
        'quantity'
    ];
    use HasFactory;
}
