<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyTractor extends Model
{
    protected $table="users-tractor";
    protected $fillable = ['user_id', 'series', 'model', 'hours', 'hour_per_week', 'start_date', 'end_date'];
    public $timestamps = false;
}
