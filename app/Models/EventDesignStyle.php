<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventDesign};

class EventDesignStyle extends Model
{

    protected $fillable = [
        'design_name'
    ];
    use HasFactory;

    public function design()
    {
        return $this->hasMany(EventDesign::class); //, 'id', 'event_design_style_id'
    }
}
