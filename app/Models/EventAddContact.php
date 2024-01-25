<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EventAddContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'country_code',
        'phone_number',
        'email',
        'prefer_by'
    ];
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
