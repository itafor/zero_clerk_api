<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['user_id','category_id','name','description'];

    public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }
       
   public function category(){
       	return $this->belongsTo(Category::class,'category_id','id');
       }

    public function purchase(){
        return $this->hasMany(Purchase::class,'sub_category_id','id');
    }

 
   public static function createNew($data)
    {
        $subcategory = self::create([
            'name' => $data['name'],
            'description' =>  $data['description'],
            'user_id' => authUser()->id,
            'category_id' => $data['category_id']
        ]); 
        
        return $subcategory;
    }

  public static function update_SubCategory($data)
    {
      $subcategory = self::where([
    		['user_id',authUser()->id],
    		['id',$data['sub_category_id']]
    	])->update([
           'name' => $data['name'],
           'category_id' => $data['category_id'],
           'description' =>  $data['description'],
        ]); 

      return $subcategory;

    }
}
