<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected  $fillable = ['course_title','course_code','credit_unit'];


   public function register(){
    	return $this->belongsTo(CourseRegister::class,'course_id','id');
    }
   
}
