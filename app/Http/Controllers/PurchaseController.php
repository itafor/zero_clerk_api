<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
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
            'item_id'=>'required',
            'quantity'=>'required',
            'industry_id'=>'required',
            'unit_cost'=>'required',
            'location_id'=>'required',
        ]);

    	$purchase = Purchase::createNew($request->all());

    	if($purchase){
    	return response(['message'=>'purchase created successfully!','purchase'=>$purchase],200);
    	}
    	return response(['error'=>'An attempt to create new purchase failed!!'],403);
    }

     public function update(Request $request,$purchase_id)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item_id'=>'required',
            'quantity'=>'required',
            'industry_id'=>'required',
            'unit_cost'=>'required',
            'location_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['purchase_id'] = $purchase_id;
    	$purchase = Purchase::updatePurchase($data);

    	if($purchase){
    	return response(['message'=>'Purchase updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update purchase failed!!'],403);
    }


     public function listPurchases(Request $request){
    	$purchases = Purchase::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory','user','supplier','location','industry','item','payments'])->get();

    	if(count($purchases) >=1 ){
    	return response()->json(['purchases'=>$purchases]);
    	}
    	return response(['error'=>'Purchases not found!!'],401);
    }

     public function fetchPurchaseById($purchase_id){
    	$purchase = Purchase::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    	['id',$purchase_id]
    	])->with(['category','subcategory','user','supplier','location','industry','item','payments'])->first();

    	if($purchase !=''){
    	return response()->json(['purchase'=>$purchase]);
    	}
    	return response(['error'=>'Purchase not found!!'],401);
    }

   public function destroyPurchase($id){
    	$purchase = Purchase::find($id);

  if($purchase){
  	$purchase->delete();
    	return response()->json(['message'=>'Purchase deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Purchase not found'],401);
    }
}
