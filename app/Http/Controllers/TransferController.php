<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{

	 public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function transferItem(Request $request)
    {
    	$validatedData = $request->validate([
            'transfering_location_id'=>'required',
            'receiving_location_id'=>'required',
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item_id'=>'required',
            'quantity'=>'required',
        ]);

        $data = $request->all();

        $receiving_item = Inventory::where([
        	['item_id',$data['item_id']],
            ['location_id',$data['receiving_location_id']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();

        $transfering_item = Inventory::where([
            ['item_id',$data['item_id']],
            ['location_id',$data['transfering_location_id']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
        ])->first();

        if(!$receiving_item){
        return response(['error'=>'Selected item or receiving location does not exist'],403);
  		}elseif (!$transfering_item) {
        return response(['error'=>'Selected item or transfering location does not exist'],403);
  		}elseif ($data['quantity'] > $transfering_item->quantity) {
        return response(['error'=>'Quantity of items to be transfered is more than available quantity'],403);
  		}else{

            $transfer = Transfer::createNew($request->all(),$transfering_item,$receiving_item);

        if($transfer){
        return response(['message'=>'Selected quantity of item has been transfered successfully!','transfer'=>$transfer],200);
        }
        return response(['error'=>'An attempt to transfer the selected quantity of item failed!!'],403);
     
  		}
    	
    }


     public function fetchTransfer(Request $request){
    	$transfers = Transfer::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['transfering_location','receiving_location','category','subcategory','item'])->get();

    	if(count($transfers) >=1 ){
    	return response()->json(['transfers'=>$transfers]);
    	}
    	return response(['error'=>'transfer not found!!'],401);
    }
}
