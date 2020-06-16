<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
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
            'inventory_id'=>'required',
            'quantity'=>'required',
            'industry_id'=>'required',
            'unit_cost'=>'required',
            'location_id'=>'required',
        ]);

        $data = $request->all();

        $inventory = Inventory::where([
            ['id',$data['inventory_id']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();

        if($inventory){
            if($data['quantity'] > $inventory->quantity){
        return response(['error'=>'Out of stock!! Quantity entered is more than available quantity'],403);
            }
        }


    	$sale = Sale::createNew($request->all());

    	if($sale){
    	return response(['message'=>'Sale created successfully!','sale'=>$sale],200);
    	}
    	return response(['error'=>'An attempt to create new sale failed!!'],403);
    }

     public function update(Request $request,$sale_id)
    {
    	$validatedData = $request->validate([
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'inventory_id'=>'required',
            'quantity'=>'required',
            'industry_id'=>'required',
            'location_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['sale_id'] = $sale_id;
    	$sale = Sale::updateSale($data);

    	if($sale){
    	return response(['message'=>'Sale updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update Sale failed!!'],403);
    }


     public function listSales(Request $request){
    	$sales = Sale::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory','user','customer','location','industry','inventory','payments'])->get();

    	if(count($sales) >=1 ){
    	return response()->json(['Sales'=>$sales]);
    	}
    	return response(['error'=>'Sales not found!!'],401);
    }

     public function fetchSaleById($sale_id){
    	$sale = Sale::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    	['id',$sale_id]
    	])->with(['category','subcategory','user','customer','location','industry','inventory','payments'])->first();

    	if($sale !=''){
    	return response()->json(['Sale'=>$sale]);
    	}
    	return response(['error'=>'Sale not found!!'],401);
    }

   public function destroySale($id){
    	$sale = Sale::find($id);

  if($sale){
  	$sale->delete();
    	return response()->json(['message'=>'Sale deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Sale not found'],401);
    }
}
