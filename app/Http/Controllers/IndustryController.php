<?php

namespace App\Http\Controllers;

use App\Industry;
use App\MyIndustry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{

 public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
	

    public function addIndustries(Request $request){

    	$validatedData = $request->validate([
            'industries'=>'required',
        ]);


    	$industry = MyIndustry::createNew($request->all());

    	if($industry){
    	return response(['message'=>'industries created Successfully!'],200);
    	}
    	return response(['error'=>'Industry creation failed!!'],401);
    }

    public function fetchUserIndustries(Request $request){
    	$my_industries = MyIndustry::where('user_id',authUser()->id)->with(['industry'])->get();
    	if(count($my_industries) >=1 ){
    	return response()->json(['My industries'=>$my_industries]);
    	}
    	return response(['error'=>'No industry found!!'],401);
    }

       public function fetchMyIndustryById($myIndustryId){
    	$my_industry = MyIndustry::where([
    		['user_id',authUser()->id],
    		['id',$myIndustryId]
    	])->with(['industry'])->first();

    	if($my_industry !=''){
    	return response()->json(['myindustry'=>$my_industry]);
    	}
    	return response(['error'=>'No industry found!!'],401);
    }


     public function edit_my_industry(Request $request, $myIndustryId){
    	$industryData = $request->validate([
    		'industry_id'=>'required'
    	]);

    	$industry = MyIndustry::where('id',$myIndustryId)
    	            ->where('user_id',authUser()->id)->first();
    	if($industry){
    		$industry->industry_id = $request->industry_id;
    		$industry->description = $request->description;
    		$industry->save();

    	return response()->json(['message'=>'Selected industry updated successfully']);
    	}
    	return response(['error'=>'Ooops!! Something went wrong'],401);
    }
}
