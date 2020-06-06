<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

      protected $fillable = [
        'business_name','contact_name','phone_number','area',
        'street_address','user_id','country_id','state_id', 
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
        return $this->hasMany(Purchase::class,'supplier_id','id');
    }

     public static function createNew($data)
    {
        $supplier = self::create([
            'business_name' => $data['business_name'],
            'contact_name' =>  $data['contact_name'],
            'phone_number' => $data['phone_number'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'user_id' => authUser()->id,
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 
        
        return $supplier;
    }

  public static function updateSupplier($data)
    {
      $supplier = self::where([
        ['id',$data['supplier_id']],
        ['user_id', authUser()->id]
    ])->update([
           'business_name' => $data['business_name'],
            'contact_name' =>  $data['contact_name'],
            'phone_number' => $data['phone_number'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 

      return $supplier;

    }

}
