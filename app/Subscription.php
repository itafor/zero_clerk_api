<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['uuid','user_id','plan_id','transaction_id','reference','status','channel'];
}


