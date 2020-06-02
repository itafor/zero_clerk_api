<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
        ]);

    	$prod_category = ProductCategory::createNew($request->all());

    	if($prod_category){
    	return response(['message'=>'product category created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new product category failed!!'],403);
    }

     public function updateProductCategory(Request $request,$prod_cat_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
        ]);

    	$data = $request->all();
    	$data['prod_cat_id'] = $prod_cat_id;
    	$product_category = ProductCategory::updateProdCategory($data);

    	if($product_category){
    	return response(['message'=>'product category updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update product category failed!!'],403);
    }


     public function listProductCategory(Request $request){
    	$product_categories = ProductCategory::where([
    		['user_id',authUser()->id]
    	])->with(['productSubCategories'])->get();

    	if(count($product_categories) >=1 ){
    	return response()->json(['product_categories'=>$product_categories]);
    	}
    	return response(['error'=>'Product categories not found!!'],401);
    }

     public function fetchProductCategoryById($prod_cat_id){
    	$product_category = ProductCategory::where([
    		['user_id',authUser()->id],
    		['id',$prod_cat_id]
    	])->with(['productSubCategories'])->first();

    	if($product_category !=''){
    	return response()->json(['product_category'=>$product_category]);
    	}
    	return response(['error'=>'Product category not found!!'],401);
    }

   public function destroyProductCategory($id){
    	$product_category = ProductCategory::find($id);

  if($product_category){
  	$product_category->delete();
    	return response()->json(['message'=>'Product category deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Product category not found'],401);
    }
}
