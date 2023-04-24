<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnPolicy extends Model
{
    protected $table = 'vendor_return_policy';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'user_id', 'location_id', 'policy_text', 'active'];

    function get_location_name()
    {
        return $this->hasOne('App\Models\StoreLocations', 'id', 'location_id');
    }
}
