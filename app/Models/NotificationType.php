<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    UserNotificationType
};

class NotificationType extends Model
{
    use HasFactory;

    public function user_notification_types()
    {
        return $this->hasMany(UserNotificationType::class, 'notification_type_id', 'id');
    }
}
