<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    ProfilePrivacy,
    User
};

class UserProfilePrivacy extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_privacy_id',
        'user_id',
        'status'
    ];
    public function profile_privacy()
    {
        $this->belongsTo(ProfilePrivacy::class, 'profile_privacy_id');
    }

    public function users()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
