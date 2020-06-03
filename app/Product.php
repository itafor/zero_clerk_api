<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
   
    protected $fillable = ['user_id','product_category_id','product_sub_category_id','name','description','selling_price'];

   public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }

   public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }

   public function subcategory()
    {
        return $this->belongsTo(ProductSubCategory::class,'product_sub_category_id','id');
    }

    public static function createNew($data)
    {
        $product = self::create([
            'name' => $data['name'],
            'product_category_id' => $data['product_category_id'],
            'product_sub_category_id' => $data['product_sub_category_id'],
            'selling_price' =>  $data['selling_price'],
            'description' =>  $data['description'],
            'user_id' => authUser()->id,
        ]); 
        
        return $product;
    }

   public static function updateProd($data)
    {
      $product = self::where([
    		['user_id',authUser()->id],
    		['id',$data['product_id']]
    	])->update([
            'name' => $data['name'],
            'product_category_id' => $data['product_category_id'],
            'product_sub_category_id' => $data['product_sub_category_id'],
            'selling_price' =>  $data['selling_price'],
            'description' =>  $data['description'],
        ]); 

      return $product;

    }
}
