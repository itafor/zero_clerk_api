<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use App\ProductSubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
     public function __construct()
    {
         $this->middleware(['auth:api','admin'],['except' => ['listProductSubCategory','fetchProductSubCategoryById','fetchProductSubCategoriesByProductCategoryId']]);
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'prod_cat_id'=>'required',
        ]);

    	$prod_subcategory = ProductSubCategory::createNew($request->all());

    	if($prod_subcategory){
    	return response(['message'=>'product subcategory created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new product subcategory failed!!'],403);
    }

    public function updateProductSubCategory(Request $request,$prod_sub_cat_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'prod_cat_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['prod_sub_cat_id'] = $prod_sub_cat_id;
    	$product_subcategory = ProductSubCategory::updateProdSubCategory($data);

    	if($product_subcategory){
    	return response(['message'=>'product subcategory updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update product subcategory failed!!'],403);
    }


     public function listProductSubCategory(Request $request){
    	$product_subcategories = ProductSubCategory::with(['productCategory'])->get();

    	if(count($product_subcategories) >=1 ){
    	return response()->json(['product_subcategories'=>$product_subcategories]);
    	}
    	return response(['error'=>'Product subcategories not found!!'],403);
    }

     public function fetchProductSubCategoryById($prod_subcat_id){
    	$product_subcategory = ProductSubCategory::where([
    		['id',$prod_subcat_id]
    	])->with(['productCategory'])->first();

    	if($product_subcategory !=''){
    	return response()->json(['product_subcategory'=>$product_subcategory]);
    	}
    	return response(['error'=>'Product subcategory not found!!'],401);
    }

   public function destroyProductSubCategory($id){
    	$product_subcategory = ProductSubCategory::find($id);

     if($product_subcategory){
  	    $product_subcategory->delete();
    	return response()->json(['message'=>'Product subcategory deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Product subcategory not found'],401);
    }

    public function fetchProductSubCategoriesByProductCategoryId($productCatId){
        $product_subcategories = ProductSubCategory::where([
            ['prod_cat_id',$productCatId],
        ])->get();

        $product_category = ProductCategory::where('id',$productCatId)->first();

        if(count($product_subcategories) >=1 ){
        return response()->json([
            'product_category'=>$product_category,
            'product_subcategories'=>$product_subcategories
        ]);
        }
        return response(['error'=>'Product subcategories not found!!'],403);
    }
}
