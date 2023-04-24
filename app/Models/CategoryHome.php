<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHome extends Model
{
    protected $table = "categories_home";
    protected $fillable = ['name', 'slug', 'photo', 'is_featured', 'image', 'skip_license', 'cod', 'order'];
    public $timestamps = false;

    public function subs()
    {
        return $this->hasMany('App\Models\Subcategory')->where('status', '=', 1);
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id', 'id');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

    public function attributes()
    {
        return $this->morphMany('App\Models\Attribute', 'attributable');
    }
}
