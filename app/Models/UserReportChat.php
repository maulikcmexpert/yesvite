<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserReportChat extends Model
{
    use HasFactory;

    public function reporter_user()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }


    public function to_reporter_user()
    {
        return $this->belongsTo(User::class, 'to_be_reported_user_id');
    }
}
