<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{

    use SoftDeletes;

    protected $fillable = ['name','description'];


   public function categories(){
       	return $this->hasMany(Category::class,'industry_id','id');
       }

   public function purchase(){
        return $this->hasMany(Purchase::class,'industry_id','id');
    }

   public function items()
    {
        return $this->hasMany(Item::class,'industry_id','id');
    }

   public static function createNew($data)
    {
        $industry = self::create([
            'name' => $data['name'],
            'description' =>  $data['description'],
        ]); 
        
        return $industry;
    }
}
