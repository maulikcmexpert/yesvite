<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventDesignSubCategory, EventDesignCategory};

class TextData extends Model
{
    use HasFactory;
    protected $table = 'text_data';
    protected $fillable = [
        'event_design_category_id',
        'event_design_sub_category_id',
        'static_information',
        'image'
    ];

    protected $casts = [
        'static_information' => 'array',
    ];

    public function subcategories()
    {
        return $this->belongsTo(EventDesignSubCategory::class, 'event_design_sub_category_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo(EventDesignCategory::class, 'event_design_category_id', 'id');
    }
}
