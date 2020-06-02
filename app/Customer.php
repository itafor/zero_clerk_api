<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
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

     public static function createNew($data)
    {
        $Customer = self::create([
            'business_name' => $data['business_name'],
            'contact_name' =>  $data['contact_name'],
            'phone_number' => $data['phone_number'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'user_id' => authUser()->id,
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 
        
        return $Customer;
    }

  public static function updateCustomer($data)
    {
      $customer = self::where('id', $data['customer_id'])->update([
           'business_name' => $data['business_name'],
            'contact_name' =>  $data['contact_name'],
            'phone_number' => $data['phone_number'],
            'street_address' => $data['street_address'],
            'area' => $data['area'],
            'user_id' => authUser()->id,
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
        ]); 

      return $customer;

    }

 
}
