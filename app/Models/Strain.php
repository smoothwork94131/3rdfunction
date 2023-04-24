<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\StrainGallery;

class Strain extends Model
{
    protected $table = 'strains';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'effect', 'description', 'parent'];

    public function galleries()
    {
        return $this->hasMany('App\Models\StrainGallery');
    }

    public function thumb() {
        $thumb = StrainGallery::where('strain_id', $this->id)->first();
        return $thumb;
    }

    public function main_thumb() {
        $main_thumb = StrainGallery::where('strain_id', $this->id)->where('index', 1)->first();
        if(!isset($main_thumb)) {
            $main_thumb = StrainGallery::where('strain_id', $this->id)->first();
        }

        return $main_thumb;
    }
}
