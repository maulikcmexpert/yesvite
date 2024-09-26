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

    public function subcategory()
    {
        return $this->hasMany(EventDesignSubCategory::class, 'design_subcategory_id');
    }
    
    public function category()
    {
        return $this->hasMany(EventDesignCategory::class, 'desgin_category_id');
    }
}
