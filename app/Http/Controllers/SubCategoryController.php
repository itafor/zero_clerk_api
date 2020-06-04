<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }
      
  public function store(Request $request)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'category_id'=>'required',
        ]);

    	$subcategory = SubCategory::createNew($request->all());

    	if($subcategory){
    	return response(['message'=>' subcategory created successfully!'],200);
    	}
    	return response(['error'=>'An attempt to create new  subcategory failed!!'],403);
    }

    public function update(Request $request,$sub_category_id)
    {
    	$validatedData = $request->validate([
            'name'=>'required',
            'category_id'=>'required',
        ]);

    	$data = $request->all();
    	$data['sub_category_id'] = $sub_category_id;
    	$subcategory = SubCategory::update_SubCategory($data);

    	if($subcategory){
    	return response(['message'=>' subcategory updated successfully!'],200);
    	}
    	return response(['error'=>'An attempt to update  subcategory failed!!'],403);
    }


     public function listSubCategory(Request $request){
    	$subcategories = SubCategory::where([
    		['user_id',authUser()->id]
    	])->with(['category'])->get();

    	if(count($subcategories) >=1 ){
    	return response()->json(['subcategories'=>$subcategories]);
    	}
    	return response(['error'=>' subcategories not found!!'],403);
    }

     public function fetchSubCategoryById($sub_category_id){
    	$subcategory = SubCategory::where([
    		['user_id',authUser()->id],
    		['id',$sub_category_id]
    	])->with(['category'])->first();

    	if($subcategory !=''){
    	return response()->json(['subcategory'=>$subcategory]);
    	}
    	return response(['error'=>' subcategory not found!!'],401);
    }

   public function destroySubCategory($id){
    	$subcategory = SubCategory::find($id);

     if($subcategory){
  	    $subcategory->delete();
    	return response()->json(['message'=>' subcategory deleted successfully']);
    	}
    	return response(['error'=>'Ooops!!  subcategory not found'],401);
    }

    public function fetchSubCategoriesByCategoryId($CatId){
        $subcategories = SubCategory::where([
            ['category_id',$CatId],
            ['user_id',authUser()->id]
        ])->get();

        $category = Category::where('id',$CatId)->first();

        if(count($subcategories) >=1 ){
        return response()->json([
            'category'=>$category,
            'subcategories'=>$subcategories
        ]);
        }
        return response(['error'=>'Subcategories not found!!'],403);
    }
}
