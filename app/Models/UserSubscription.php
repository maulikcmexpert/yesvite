<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function coin_transactions()
    {
        return $this->hasOne(Coin_transactions::class, 'user_subscription_id', 'id');
    }
}
