<?php

namespace App\Http\Controllers;

use App\Industry;
use App\MyIndustry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{

 public function __construct()
    {
        $this->middleware(['auth:api','admin'],['except' => ['fetchIndustries','fetchIndustryById']]);
    }
	

    public function store(Request $request){

    	$validatedData = $request->validate([
            'name'=>'required',
        ]);


    	$industry = Industry::createNew($request->all());

    	if($industry){
    	return response(['message'=>'industries created Successfully!'],200);
    	}
    	return response(['error'=>'Industry creation failed!!'],401);
    }

    public function fetchIndustries(Request $request){
        $industries = Industry::with(['categories'])->get();
        if(count($industries) >=1 ){
        return response()->json(['categories'=>$industries]);
        }
        return response(['error'=>'No industry found!!'],401);
    }

     public function fetchIndustryById($industry_id){

        $industry = Industry::where([
            ['id',$industry_id]
        ])->with(['categories'])->first();

        if($industry !=''){
        return response()->json(['industry'=>$industry]);
        }
        return response(['error'=>' industry not found!!'],401);
    }
    
}
