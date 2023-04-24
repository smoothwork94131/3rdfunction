<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductClick extends Model
{
    protected $table = 'product_clicks';
    protected $fillable = ['user_id', 'category_id',  'product_id', 'search_term', 'action'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function get_user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function get_product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function get_category() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
}
