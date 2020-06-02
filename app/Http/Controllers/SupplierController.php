<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'business_name'=>'required',
            'contact_name'=>'required',
            'phone_number'=>'required|unique:suppliers',
            'area'=>'required',
            'street_address'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
        ]);

    	$supplier = Supplier::createNew($request->all());

    	if($supplier){
    	return response(['message'=>'supplier created successfully!','supplier'=>$supplier],200);
    	}
    	return response(['error'=>'An attempt to create new supplier failed!!'],403);
    }

     public function update(Request $request,$supplier_id)
    {
    	$validatedData = $request->validate([
            'business_name'=>'required',
            'contact_name'=>'required',
            'phone_number'=>'required',
            'area'=>'required',
            'street_address'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['supplier_id'] = $supplier_id;
    	$supplier = Supplier::updateSupplier($data);

    	if($supplier){
    	return response(['message'=>'supplier updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update supplier failed!!'],403);
    }


     public function listSuppliers(Request $request){
    	$suppliers = Supplier::where([
    		['user_id',authUser()->id]
    	])->with(['country','state','user'])->get();

    	if(count($suppliers) >=1 ){
    	return response()->json(['suppliers'=>$suppliers]);
    	}
    	return response(['error'=>'Suppliers not found!!'],401);
    }

     public function fetchSupplierById($supplier_id){
    	$supplier = Supplier::where([
    		['user_id',authUser()->id],
    		['id',$supplier_id]
    	])->with(['country','state','user'])->first();

    	if($supplier !=''){
    	return response()->json(['supplier'=>$supplier]);
    	}
    	return response(['error'=>'supplier not found!!'],401);
    }

   public function destroySupplier($id){
    	$supplier = Supplier::find($id);

  if($supplier){
  	$supplier->delete();
    	return response()->json(['message'=>'supplier deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! supplier not found'],401);
    }
}
