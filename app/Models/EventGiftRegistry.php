<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGiftRegistry extends Model
{
    protected $fillable = [
        'user_id',
        'registry_recipient_name',
        'registry_link'
    ];
    use HasFactory;
}
