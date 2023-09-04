<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{

    protected $table = 'companies';
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'company_name',
        'email',
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
