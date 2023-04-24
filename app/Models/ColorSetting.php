<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorSetting extends Model
{
    protected $table = 'colorsetting';
    protected $fillable = ['id', 'type', 'style_id', 'title_color', 'tag_bg_color', 'tag_color', 'detail_color', 'sub_detail_color', 'price_color', 'buttons_color'];
    public $timestamps = false;
}
