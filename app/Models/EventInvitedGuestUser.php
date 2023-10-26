<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInvitedGuestUser extends Model
{
    protected $fillable = [
        'event_id', // Add this line if it's not already present    
        'first_name',
        'last_name',
        'email',
        'country_code',
        'phone_number',
    ];
    use HasFactory;
}
