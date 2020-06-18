<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['category_id','sub_category_id','item','description'];

     public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

   public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

  public static function createNew($data)
    {
        $item = self::create([
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'item' => $data['item'],
            'description' =>  $data['description'],
        ]); 
        
        return $item;
    }

      public static function updateItem($data)
    {
      $item = self::where([
    		['id',$data['item_id']]
    	])->update([
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'item' => $data['item'],
            'description' =>  $data['description'],
        ]); 

      return $item;

    }
}
