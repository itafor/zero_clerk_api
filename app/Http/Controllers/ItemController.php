<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api','admin'],['except' => ['listItems','fetchItemById','fetchItemsByCategoryId','fetchItemsBySubCategoryId']]);
    }

    public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'industry_id'=>'required',
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item'=>'required',
        ]);

        $data = $request->all();

        $item = Item::where([ 
            ['category_id',$data['category_id']],
            ['sub_category_id',$data['sub_category_id']],
            ['item',$data['item']],
        ])->first();
        
        if($item){
        return response(['error'=>'Item already exist!!'],403);
        }

    	$item = Item::createNew($request->all());

    	if($item){
    	return response(['message'=>'Item created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new item failed!!'],403);
    }

 public function update(Request $request,$item_id)
    {
    	$validatedData = $request->validate([
            'industry_id'=>'required',
            'category_id'=>'required',
            'sub_category_id'=>'required',
            'item'=>'required',
        ]);

    	$data = $request->all();
    	$data['item_id'] = $item_id;
    	$item = Item::updateProd($data);

    	if($item){
    	return response(['message'=>'Item updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update item failed!!'],403);
    }


 public function listItems(Request $request){
    	$items = Item::with(['category','subcategory','industry'])->get();

    	if(count($items) >=1 ){
    	return response()->json(['items'=>$items]);
    	}
    	return response(['error'=>'items not found!!'],401);
    }

 public function fetchItemById($item_id){
    	$item = Item::where([
    		['id',$item_id]
    	])->with(['category','subcategory','industry'])->first();

    	if($item !=''){
    	return response()->json(['item'=>$item]);
    	}
    	return response(['error'=>'item not found!!'],401);
    }

 public function destroyItem($id){
    	$item = Item::find($id);

  if($item){
  	$item->delete();
    	return response()->json(['message'=>'item deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! item not found'],401);
    }

    public function fetchItemsByCategoryId($categoryId){
        $items = Item::where([
            ['category_id',$categoryId]
        ])->with(['category','subcategory','industry'])->get();

        if(count($items) >= 1){
        return response()->json(['items'=>$items]);
        }
        return response(['error'=>'Item not found!!'],401);
    }

    public function fetchItemsBySubCategoryId($subcategoryId){
        $items = Item::where([
            ['sub_category_id',$subcategoryId]
        ])->with(['category','subcategory','industry'])->get();

        if(count($items) >= 1){
        return response()->json(['items'=>$items]);
        }
        return response(['error'=>'Item not found!!'],401);
    }

    public function fetchItemsByIndustry($industry_id){
        $items = Item::where([
            ['industry_id',$industry_id]
        ])->with(['category','subcategory','industry'])->get();

        if(count($items) >= 1){
        return response()->json(['items'=>$items]);
        }
        return response(['error'=>'Item not found!!'],401);
    }
}
