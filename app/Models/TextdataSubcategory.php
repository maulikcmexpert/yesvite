<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextdataSubcategory extends Model
{
    use HasFactory;
    protected $table = 'textdata_subcategories';
    protected $fillable = [
        'template_id',
        'subcategory_id',
      
    ];
    public function subcategories()
    {
        return $this->belongsTo(EventDesignSubCategory::class, 'subcategory_id', 'id');
    }
    public function template()
    {
        return $this->belongsTo(TextData::class, 'template_id', 'id');
    }


}
