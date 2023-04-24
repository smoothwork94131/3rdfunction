<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifiedLicense extends Model
{
    protected $table = 'verified_license';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'user_id', 'license1', 'license2', 'license3', 'driver_license', 'bill_license', 'expires', 'verified_default'];

    public function get_User()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
