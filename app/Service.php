<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
     use SoftDeletes;

      protected $fillable = [
        'category_id','sub_category_id','user_id','name','selling_price', 
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'sub_category_id','id');
    }

     public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

     public static function createNew($data){

        $service = self::create([
            'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'name' => $data['name'],
            'selling_price' => $data['selling_price'],
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
        ]); 
        
        return $service;
    }

     public static function update_service($data)
    {
      $service = self::where([
        ['id',$data['service_id']],
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
    ])->update([
            'category_id' => $data['category_id'],
            'sub_category_id' =>  $data['sub_category_id'],
            'name' => $data['name'],
            'selling_price' => $data['selling_price'],
        ]); 

      return $service;

    }
}
