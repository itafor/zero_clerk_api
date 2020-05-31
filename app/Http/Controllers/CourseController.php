<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseRegister;
use App\Http\Resources\CourseResource;
use App\Jobs\CreateCourses;
use Illuminate\Container\factory;
use Illuminate\Http\Request;

class CourseController extends Controller
{

	 public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['createCourses']]);
    }


    public function createCourses(){

    	$create_courses_job = CreateCourses::dispatch()
            ->delay(now()->addSeconds(3));

		if($create_courses_job){
			return response(['message'=>'Courses created successfully'],200);
		}    
			return response(['error'=>'Course(s) creation Failed!'],500);

    }

   public function courseRegistration(Request $request){

    	$courseData = $request->validate([
    		'courses'=>'required'
    	]);

    		$registerCourses = CourseRegister::where('user_id',auth()->user()->id)->get();

    		if(count($registerCourses) >=1){
    			foreach ($registerCourses as $regCourse) {
    				foreach ($courseData['courses'] as  $course) {
    					if($regCourse->course_id == $course['course_id']){
		                	return response(['error'=>'You have already registered the selected course(s)']);
    					}
    				}
    				
    			}
    		}
    
    	$course = CourseRegister::createNew($courseData);
    	
			return response(['success'=>'Course(s) registered successfully'],200);
    

    }


    public function listCourses(){
    	 $courses = CourseResource::collection(Course::with('register')->paginate(25));

      if(count($courses) >=1 ){
			return response(['courses'=>$courses],200);
		}    
			return response(['error'=>'No course(s) found'],500);

    }

}
