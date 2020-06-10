<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSubCategory extends Model
{
    use SoftDeletes;
   
    protected $fillable = ['user_id','prod_cat_id','name','description'];

    public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }
       
   public function productCategory(){
       	return $this->belongsTo(ProductCategory::class,'prod_cat_id','id');
       }

  public function products(){
            return $this->hasMany(Product::class,'product_sub_category_id','id');
        }

   public static function createNew($data)
    {
        $product_subcategory = self::create([
            'name' => $data['name'],
            'description' =>  $data['description'],
            // 'user_id' => authUser()->id,
            'prod_cat_id' => $data['prod_cat_id']
        ]); 
        
        return $product_subcategory;
    }

  public static function updateProdSubCategory($data)
    {
      $product_subcategory = self::where([
    		// ['user_id',authUser()->id],
    		['id',$data['prod_sub_cat_id']]
    	])->update([
           'name' => $data['name'],
           'prod_cat_id' => $data['prod_cat_id'],
           'description' =>  $data['description'],
        ]); 

      return $product_subcategory;

    }
}
