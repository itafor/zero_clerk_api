<?php

namespace App\Exports;

use App\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class CsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return Course::all();
        $courses = DB::table('courses')->get()->toArray();

        $course_array[] = array('Course Title','Course Code','Credit Unit');

        foreach ($courses as $course) {
           $course_array[] = array(
                'Course Title' => $course->course_title,
                'Course Code' => $course->course_code,
                'Credit Unit' => $course->credit_unit
           );
        }

        return collect($course_array);
    }
}
