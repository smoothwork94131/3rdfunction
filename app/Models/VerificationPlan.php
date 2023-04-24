<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationPlan extends Model
{
    protected $fillable = ['title', 'type', 'price', 'details'];
    public $timestamps = false;
}