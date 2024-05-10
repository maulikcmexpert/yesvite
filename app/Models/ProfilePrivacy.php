<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    UserProfilePrivacy
};

class ProfilePrivacy extends Model
{
    use HasFactory;

    public function user_profile_privacy()
    {
        $this->hasMany(UserProfilePrivacy::class, 'profile_privacy_id', 'id');
    }
}
