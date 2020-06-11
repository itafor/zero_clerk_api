<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
 
 public function __construct()
    {
        $this->middleware('auth:api');
    }
      
 public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'product_category_id'=>'required',
            'product_sub_category_id'=>'required',
            'name'=>'required',
            'selling_price'=>'required',
        ]);

        $data = $request->all();

        $product = Product::where([ 
            ['product_category_id',$data['product_category_id']],
            ['product_sub_category_id',$data['product_sub_category_id']],
            ['name',$data['name']],
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
        ])->first();
        if($product){
        return response(['error'=>'Product already exist!!'],403);
        }

    	$product = Product::createNew($request->all());

    	if($product){
    	return response(['message'=>'product created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new product failed!!'],403);
    }

 public function updateProduct(Request $request,$product_id)
    {
    	$validatedData = $request->validate([
            'product_category_id'=>'required',
            'product_sub_category_id'=>'required',
            'name'=>'required',
            'selling_price'=>'required',
        ]);

    	$data = $request->all();
    	$data['product_id'] = $product_id;
    	$product = Product::updateProd($data);

    	if($product){
    	return response(['message'=>'product updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update product failed!!'],403);
    }


 public function listProducts(Request $request){
    	$products = Product::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    	])->with(['category','subcategory'])->get();

    	if(count($products) >=1 ){
    	return response()->json(['products'=>$products]);
    	}
    	return response(['error'=>'Product not found!!'],401);
    }

 public function fetchProductById($prod_id){
    	$product = Product::where([
    		['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
    		['id',$prod_id]
    	])->with(['category','subcategory'])->first();

    	if($product !=''){
    	return response()->json(['product'=>$product]);
    	}
    	return response(['error'=>'Product not found!!'],401);
    }

 public function destroyProduct($id){
    	$product = Product::find($id);

  if($product){
  	$product->delete();
    	return response()->json(['message'=>'Product deleted successfully']);
    	}
    	return response(['error'=>'Ooops!! Product not found'],401);
    }

    public function fetchProductsByCategoryId($categoryId){
        $products = Product::where([
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
            ['product_category_id',$categoryId]
        ])->with(['category','subcategory'])->get();

        if(count($products) >= 1){
        return response()->json(['products'=>$products]);
        }
        return response(['error'=>'Product not found!!'],401);
    }

    public function fetchProductsBySubCategoryId($subcategoryId){
        $products = Product::where([
            ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id],
            ['product_sub_category_id',$subcategoryId]
        ])->with(['category','subcategory'])->get();

        if(count($products) >= 1){
        return response()->json(['products'=>$products]);
        }
        return response(['error'=>'Product not found!!'],401);
    }
}
