<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
	use SoftDeletes;
   
    protected $fillable = ['user_id','name','description'];

    public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }

    public function productSubCategories(){
            return $this->hasMany(ProductSubCategory::class,'prod_cat_id','id');
        }

    public function products(){
            return $this->hasMany(Product::class,'product_category_id','id');
        }

  public static function createNew($data)
    {
        $product_category = self::create([
            'name' => $data['name'],
            'description' =>  $data['description'],
            'user_id' => authUser()->id,
        ]); 
        
        return $product_category;
    }

   public static function updateProdCategory($data)
    {
      $product_category = self::where([
    		['user_id',authUser()->id],
    		['id',$data['prod_cat_id']]
    	])->update([
           'name' => $data['name'],
           'description' =>  $data['description'],
        ]); 

      return $product_category;

    }
}
