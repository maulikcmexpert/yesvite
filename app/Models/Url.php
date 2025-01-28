<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['long_url', 'short_url_key', 'expires_at'];

    protected $dates = ['expires_at'];
}
