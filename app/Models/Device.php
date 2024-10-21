<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Device extends Model
{
    use HasFactory;
    public $model;
protected $table = 'devices';

    protected $fillable = ['device_token', 'model'];

    // Getter for model
    public function getModel()
    {
        return $this->model;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
