<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDesignFonts extends Model
{
    use HasFactory;
    protected $table = 'event_design_fonts';
    protected $fillable = [
        'font_ttf_file',
        'font_web_file',
        'font_name'
    ];
}
