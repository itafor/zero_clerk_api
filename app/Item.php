<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['category_id','sub_category_id','industry_id','item','description'];

     public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

   public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class,'industry_id','id');
    }

     public function purchase(){
        return $this->hasMany(Purchase::class,'item_id','id');
    }

   public function sales(){
        return $this->hasMany(Sale::class,'item_id','id');
    }

     public function transfers()
    {
        return $this->hasMany(Transfer::class,'item_id','id');
    }

  public static function createNew($data)
    {
        $item = self::create([
            'industry_id' => $data['industry_id'],
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
            'industry_id' => $data['industry_id'],
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'item' => $data['item'],
            'description' =>  $data['description'],
        ]); 

      return $item;

    }
}
