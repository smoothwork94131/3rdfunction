<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrainGallery extends Model
{
    protected $table = 'strain_gallery';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'strain_id', 'path', 'extension', 'name', 'index'];
}
