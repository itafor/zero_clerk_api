<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseRegister extends Model
{
    protected $fillable = ['course_id','user_id'];

    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }

     public function courses(){
    	return $this->hasMany(Course::class);
    }

    public static function createNew($data){

    	foreach ($data['courses'] as $key => $course) {
    	 self::create([
    		'course_id' => $course['course_id'],
    		'user_id' => auth()->user()->id,
    	]);

    	}

    }
}
