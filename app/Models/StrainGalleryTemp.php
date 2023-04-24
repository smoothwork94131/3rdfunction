<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrainGalleryTemp extends Model
{
    //
    protected $table = 'strain_gallery_temp';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'strain_temp_id', 'path'];
}
