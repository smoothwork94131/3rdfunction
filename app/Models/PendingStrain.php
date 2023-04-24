<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingStrain extends Model
{
    protected $fillable = ['product_id'];
    public $timestamps = false;

    function get_product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
