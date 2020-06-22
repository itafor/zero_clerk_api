<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
   
    protected $fillable = ['industry_id','name','description'];

    public function user(){
       	return $this->belongsTo(User::class,'user_id','id');
       }

    public function subCategories(){
            return $this->hasMany(SubCategory::class,'category_id','id');
        }

      public function purchase(){
        return $this->hasMany(Purchase::class,'category_id','id');
    }

   public function items(){
            return $this->hasMany(Item::class,'category_id','id');
        }

  public function expenses(){
            return $this->hasMany(Expense::class,'category_id','id');
        }

         public function industry(){
            return $this->belongsTo(Industry::class,'industry_id','id');
        }


  public static function createNew($data)
    {
        $category = self::create([
            'name' => $data['name'],
            'industry_id' => $data['industry_id'],
            'description' =>  $data['description'],
        ]); 
        
        return $category;
    }

   public static function updateCategory($data)
    {
      $category = self::where([
    		['id',$data['category_id']]
    	])->update([
           'name' => $data['name'],
           'industry_id' => $data['industry_id'],
           'description' =>  $data['description'],
        ]); 

      return $category;

    }
}
