<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignOption extends Model
{
    //
    protected $table = 'text_campaign_options';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = ['number_texts', 'price'];
}
