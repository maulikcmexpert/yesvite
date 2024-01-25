<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{
    EventDesignCategory,
    EventDesignSubCategory,
    EventDesignStyle,
    EventDesignColor
};

class EventDesign extends Model
{
    protected $fillable = [
        'event_design_category_id',
        'event_design_subcategory_id',
        'event_design_style_id',
        'image'
    ];
    use HasFactory;

    public function category()
    {
        return  $this->belongsTo(EventDesignCategory::class, 'event_design_category_id', 'id');
    }
    public function subcategory()
    {
        return $this->belongsTo(EventDesignSubCategory::class, 'event_design_subcategory_id', 'id');
    }
    public function design_style()
    {
        return $this->belongsTo(EventDesignStyle::class, 'event_design_style_id', 'id');
    }

    public function design_colors()
    {
        return $this->hasMany(EventDesignColor::class);
    }
}
