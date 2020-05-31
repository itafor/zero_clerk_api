<?php

namespace App\Http\Resources;

use App\CourseRegister;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{

    public static function dateEnrolled($userId,$courseId){

            $registerDate = CourseRegister::where('user_id',$userId)
                                  ->where('course_id',$courseId)->first();
            if($registerDate){
                return  \Carbon\Carbon::parse($registerDate->created_at)->format('d/m/Y');
            }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'=> $this->id,
            'Course Title'=> $this->course_title,
            'Course Code' => $this->course_code,
            'Credit Unit'=>$this->credit_unit,
            'date enrolled'=> self::dateEnrolled(auth()->user()->id,$this->id),
        ];
    }
}
