<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Device extends Model
{
    use HasFactory;
    protected $fillable = ['device_token', 'model']; // Add 'model' to fillable attributes


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
