<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrainTemp extends Model
{
    protected $table = 'strain_temp';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'user_id', 'strain_id', 'path', 'effect', 'description', 'parent'];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function galleries()
    {
        return $this->hasMany('App\Models\StrainGalleryTemp', 'strain_temp_id');
    }
}
