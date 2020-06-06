<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
     public function purchase(){
        return $this->hasMany(Purchase::class,'industry_id','id');
    }
}
