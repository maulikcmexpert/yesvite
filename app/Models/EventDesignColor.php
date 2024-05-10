<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventDesign;

class EventDesignColor extends Model
{
    protected $fillable = [
        'event_design_id',
        'event_design_color'
    ];
    use HasFactory;

    public function event_design()
    {
        return $this->belongsTo(EventDesign::class);
    }
}
