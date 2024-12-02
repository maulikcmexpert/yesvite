<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact_sync extends Model
{
    use HasFactory;
    protected $table= 'contact_sync';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'isAppUser',
        'phone',
        'phoneWithCode',
        'photo',
        'contact_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
