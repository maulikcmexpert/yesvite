<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    NotificationType,
    User
};

class UserNotificationType extends Model
{
    use HasFactory;

    public function notification_types()
    {
        return $this->belongsTo(NotificationType::class, 'notificaton_type_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
