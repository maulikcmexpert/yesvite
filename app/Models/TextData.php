<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventDesignSubCategory, EventDesignCategory,Admin};

class TextData extends Model
{
    use HasFactory;
    protected $table = 'text_data';
    protected $fillable = [
        'creator_id',
        'event_design_category_id',
        'event_design_sub_category_id',
        'static_information',
        'image'
    ];

    protected $casts = [
        'static_information' => 'array',
    ];
    // public function subcategories()
    // {
    //     return $this->belongsToMany(EventDesignSubCategory::class, 'textdata_subcategories', 'subcategory_id', 'id');
    // }
    public function subcategories()
{
    return $this->belongsToMany(
        EventDesignSubCategory::class,
        'textdata_subcategories',
        'textdata_id',       // Foreign key on pivot table for this (TextData) model
        'subcategory_id'     // Foreign key on pivot table for the related model
    );
}

    // public function subcategories()
    // {
    //     return $this->belongsTo(EventDesignSubCategory::class, 'event_design_sub_category_id', 'id');
    //     // return $this->belongsToMany(EventDesignSubCategory::class, 'textdata_subcategories', 'subcategory_id');

       
    
    
    public function categories()
    {
        return $this->belongsTo(EventDesignCategory::class, 'event_design_category_id', 'id');
    }

    public function admins()
    {
        return $this->belongsTo(Admin::class,'creator_id','id');
    }
}
