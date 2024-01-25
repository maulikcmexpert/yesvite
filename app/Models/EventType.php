<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $fillable = [
        'event_type'
    ];
    use HasFactory;
}
