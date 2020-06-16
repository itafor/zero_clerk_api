<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

	 public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function fetchInventoriesByLocation($location_id){
    	$inventories = Inventory::where([
    	   ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    	   ['location_id',$location_id]
    	])->get();

    	if(count($inventories) >=1 ){
    	return response()->json(['inventories'=>$inventories]);
    	}
    	return response(['error'=>'Inventories not found!!'],401);
    }

    public function listInventories(Request $request){
    	$inventories = Inventory::where([
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['user','purchase','location'])->get();

    	if(count($inventories) >=1 ){
    	return response()->json(['inventories'=>$inventories]);
    	}
    	return response(['error'=>'inventories not found!!'],401);
    }
}
