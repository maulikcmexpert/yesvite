<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextData extends Model
{
    use HasFactory;
    protected $table = 'text_data';
    protected $fillable = [
        'static_information',
        'image'
    ];

    protected $casts = [
        'static_information' => 'array',
    ];

    public function subcategories()
    {
        return $this->belongsToMany(EventDesignSubCategory::class,'event_design_sub_category_id', 'id');
    }
    
    public function categories()
    {
        return $this->belongsToMany(EventDesignCategory::class,'event_design_category_id', 'id');
    }
}
