<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGiftRegistry extends Model
{
    protected $fillable = [
        'event_id',
        'registry_recipient_name',
        'registry_link'
    ];
    use HasFactory;
}
