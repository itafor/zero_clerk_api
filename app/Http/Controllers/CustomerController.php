<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
            'phone_number'=>'required',
            'area'=>'required',
            'street_address'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
        ]);

    	$customer = Customer::createNew($request->all());

    	if($customer){
    	return response(['message'=>'Customer created successfully!','Customer'=>$customer],200);
    	}
    	return response(['error'=>'An attempt to create new customer failed!!'],403);
    }

     public function update(Request $request,$customer_id)
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
    	$data['customer_id'] = $customer_id;
    	$customer = Customer::updateCustomer($data);

    	if($customer){
    	return response(['message'=>'Customer updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update customer failed!!'],403);
    }


     public function listCustomers(Request $request){
    	$customers = Customer::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['country','state','user'])->get();

    	if(count($customers) >=1 ){
    	return response()->json(['customers'=>$customers]);
    	}
    	return response(['error'=>'customers not found!!'],401);
    }

     public function fetchCustomerById($customer_id){
    	$customer = Customer::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    		['id',$customer_id]
    	])->with(['country','state','user'])->first();

    	if($customer !=''){
    	return response()->json(['customer'=>$customer]);
    	}
    	return response(['error'=>'customer not found!!'],401);
    }

   public function destroyCustomer($id){
    	$customer = Customer::find($id);

  if($customer){
  	$Customer->delete();
    	return response()->json(['message'=>'Customer deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Customer not found'],401);
    }
}
