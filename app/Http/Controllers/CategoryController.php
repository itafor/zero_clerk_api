<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     public function __construct()
    {
        $this->middleware(['auth:api','admin'],['except' => ['listCategories','fetchCategoryById','fetchCategoriesByIndustry']]);
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'industry_id'=>'required',
        ]);

    	$prod_category = Category::createNew($request->all());

    	if($prod_category){
    	return response(['message'=>' category created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new  category failed!!'],403);
    }

     public function update(Request $request,$category_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
        ]);

    	$data = $request->all();
    	$data['category_id'] = $category_id;
    	$category = Category::updateCategory($data);

    	if($category){
    	return response(['message'=>' category updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update  category failed!!'],403);
    }


     public function listCategories(Request $request){
    	$categories = Category::with(['subCategories','industry'])->get();

    	if(count($categories) >=1 ){
    	return response()->json(['categories'=>$categories]);
    	}
    	return response(['error'=>' categories not found!!'],401);
    }

     public function fetchCategoryById($prod_cat_id){
    	$category = Category::where([
    		['id',$prod_cat_id]
    	])->with(['subCategories','industry'])->first();

    	if($category !=''){
    	return response()->json(['category'=>$category]);
    	}
    	return response(['error'=>' category not found!!'],401);
    }

   public function destroyCategory($id){
    	$category = Category::find($id);

  if($category){
  	$category->delete();
    	return response()->json(['message'=>' Category deleted successfully']);
    	}
    	return response(['error'=>'Ooops!!  Category not found'],401);
    }

  public function fetchCategoriesByIndustry($industry_id){
        $categories = Category::where([
            ['industry_id',$industry_id],
        ])->with(['subCategories','industry'])->get();

        if(count($categories) >=1 ){
        return response()->json([
            'categories'=>$categories,
        ]);
        }
        return response(['error'=>'categories not found!!'],403);
    }
}
