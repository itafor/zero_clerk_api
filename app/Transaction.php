<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
   use SoftDeletes;
   
    protected $fillable = ['uuid','user_id','plan_id','amount','reference','status','channel','provider_reference'];
}
