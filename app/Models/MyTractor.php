<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyTractor extends Model
{
    protected $table="users-tractor";
    protected $fillable = ['user_id', 'category_id', 'product_id', 'hours', 'hour_per_week', 'start_date', 'end_date'];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Models\Category')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
}
