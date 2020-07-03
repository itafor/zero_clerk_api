<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
     
     public function __construct()
    {
        $this->middleware(['auth:api','admin'],['except' => ['listPlans','fetchPlanById']]);
    }

       
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'number_of_subusers'=>'required',
            'number_of_industry'=>'required',
            'number_of_transaction'=>'required',
            'amount'=>'required',
        ]);

        $data = $request->all();

            $plan = SubscriptionPlan::createNew($request->all());

        if($plan){
        return response(['message'=>'plan created successfully!','plan'=>$plan],200);
        }
        return response(['error'=>'An attempt to create new plan failed!!'],403);
    }

 public function update(Request $request,$plan_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'number_of_subusers'=>'required',
            'number_of_industry'=>'required',
            'number_of_transaction'=>'required',
            'amount'=>'required',
        ]);

    	$data = $request->all();
    	$data['plan_id'] = $plan_id;
    	$plan = SubscriptionPlan::updatePlan($data);

    	if($plan){
    	return response(['message'=>'plan updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update plan failed!!'],403);
    }


  public function listPlans(Request $request){
    	$plans = SubscriptionPlan::all();

    	if(count($plans) >=1 ){
    	return response()->json(['plans'=>$plans]);
    	}
    	return response(['error'=>'plans not found!!'],401);
    }

 public function fetchPlanById($plan_id){
    	$plan = SubscriptionPlan::where([
    	['id',$plan_id]
    	])->first();

    	if($plan !=''){
    	return response()->json(['plan'=>$plan]);
    	}
    	return response(['error'=>'plan not found!!'],401);
    }

public function destroyPlan($id){
    	$plan = SubscriptionPlan::find($id);

  if($plan){
  	$plan->delete();
    	return response()->json(['message'=>'plan deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! plan not found'],401);
    }
}
