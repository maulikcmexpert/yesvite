<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventDesignCategory;

class EventDesignSubCategory extends Model
{
    protected $fillable = [

        'event_design_category_id',
        'subcategory_name'
    ];
    use HasFactory;


    public function category()
    {
        return $this->belongsTo(EventDesignCategory::class, 'event_design_category_id', 'id');
    }
}
