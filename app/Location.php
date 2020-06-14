<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
	use SoftDeletes;

     protected $fillable = [
        'name','type','country_id','state_id',
        'area','street_address','user_id'
    ];

      public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function state(){
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function purchase(){
        return $this->hasMany(Purchase::class,'location_id','id');
    }

   public static function createNew($data)
    {
        $Location = self::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 
        
        return $Location;
    }

  public static function update_location($data)
    {
      $Location = self::where([
        ['id', $data['location_id']],
        ['user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id]
  ])->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 

      return $Location;

    }

}
