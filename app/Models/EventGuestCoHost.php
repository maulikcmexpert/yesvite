<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGuestCoHost extends Model
{
    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone_number'
    ];
    use HasFactory;
}
