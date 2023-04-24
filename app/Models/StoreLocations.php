<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLocations extends Model
{
    protected $fillable = ['address', 'city', 'state', 'zip', 'country', 'lat', 'lng', 'user_id', 'contact_name', 'contact_number', 'contact_email', 'location_name'];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'location_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
}
