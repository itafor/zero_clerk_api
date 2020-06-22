<?php

namespace App\Http\Controllers;

use App\MyIndustry;
use Illuminate\Http\Request;

class UsersIndustryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
	

    public function addUserIndustries(Request $request){

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
    	$my_industries = MyIndustry::where('user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id
       )->with(['industry'])->get();
    	if(count($my_industries) >=1 ){
    	return response()->json(['myindustries'=>$my_industries]);
    	}
    	return response(['error'=>'No industry found!!'],401);
    }

       public function fetchMyIndustryById($myIndustryId){
    	$my_industry = MyIndustry::where([
    		['user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
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

    	$industry = MyIndustry::where([
            ['id',$myIndustryId],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
        ])->first();
    	if($industry){
    		$industry->industry_id = $request->industry_id;
    		$industry->description = $request->description;
    		$industry->save();

    	return response()->json(['message'=>'Selected industry updated successfully']);
    	}
    	return response(['error'=>'Ooops!! Something went wrong'],401);
    }

    public function destroyUserIndustry($id){
        $industry = MyIndustry::find($id);

  if($industry){
    $industry->delete();
        return response()->json(['message'=>'Industry deleted successfully']);
        }
        return response(['error'=>'Ooops!! Industry not found'],401);
    }
}
