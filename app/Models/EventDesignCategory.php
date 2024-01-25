<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EventDesignSubCategory, EventDesign};


class EventDesignCategory extends Model
{

    protected $fillable = [
        'category_name',

    ];
    use HasFactory;

    public function subcategory()
    {
        return $this->hasMany(EventDesignSubCategory::class, 'event_design_category_id', 'id');
    }

    public function design()
    {
        return $this->hasMany(EventDesign::class); //, 'event_design_category_id', 'id'
    }
}
