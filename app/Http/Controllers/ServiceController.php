<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
 public function __construct()
    {
        $this->middleware('auth:api');
    }
      
 public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'name'=>'required',
            'selling_price'=>'required',
        ]);

        $data = $request->all();

        $service = Service::where([ 
            ['category_id',$data['category_id']],
            ['sub_category_id',$data['sub_category_id']],
            ['name',$data['name']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
        ])->first();
        if($service){
        return response(['error'=>'Service already exist!!'],403);
        }

    	$service = Service::createNew($request->all());

    	if($service){
    	return response(['message'=>'Service created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new Service failed!!'],403);
    }

 public function update(Request $request,$service_id)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'name'=>'required',
            'selling_price'=>'required',
        ]);

    	$data = $request->all();
    	$data['service_id'] = $service_id;
    	$service = Service::update_service($data);

    	if($service){
    	return response(['message'=>'Service updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update Service failed!!'],403);
    }


 public function listServices(Request $request){
    	$service = Service::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory'])->get();

    	if(count($service) >=1 ){
    	return response()->json(['service'=>$service]);
    	}
    	return response(['error'=>'Service not found!!'],401);
    }

 public function fetchServiceById($prod_id){
    	$service = Service::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    		['id',$prod_id]
    	])->with(['category','subcategory'])->first();

    	if($service !=''){
    	return response()->json(['service'=>$service]);
    	}
    	return response(['error'=>'Service not found!!'],401);
    }

 public function destroyService($id){
    	$service = Service::find($id);

  if($service){
  	$service->delete();
    	return response()->json(['message'=>'Service deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Service not found'],401);
    }

}
