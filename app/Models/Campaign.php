<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    //
    protected $table = 'text_campaigns';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = ['message', 'link', 'approved', 'option_id', 'vendor_id'];

    public function get_option()
    {
        return $this->hasOne('App\Models\CampaignOption', 'id', 'option_id');
    }

    public function get_user(){
        return $this->hasOne('App\Models\User', 'id', 'vendor_id');
    }
}
