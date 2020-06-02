<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyIndustry extends Model
{
    protected $fillable = ['industry_id','user_id','description'];

   public function industry(){
    		return $this->belongsTo(Industry::class,'industry_id','id');
    	}

   public static function createNew($data){

    	foreach ($data['industries'] as $key => $industry) {
    	$my_industries = self::create([
    		'industry_id' => $industry['industry_id'],
    		'user_id' => authUser()->id,
    		'description' => $industry['description'],
    	]);

    	}
  	return $my_industries;

    }
}
