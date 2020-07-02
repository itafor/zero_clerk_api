<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
     use SoftDeletes;
   
    protected $fillable = ['uuid','name','number_of_subusers','number_of_industry','number_of_transaction','amount','description'];


      public static function createNew($data){

        $plan = self::create([
            'uuid' => generateUUID(),
            'name' => $data['name'],
            'number_of_subusers' =>  $data['number_of_subusers'],
            'number_of_industry' => $data['number_of_industry'],
            'number_of_transaction' => $data['number_of_transaction'],
            'amount' => $data['amount'],
            'description' => $data['description'],
        ]); 
        
        return $plan;
    }

    public static function update_plan($data)
    {
      $plan = self::where([
        ['id',$data['plan_id']],
    ])->update([
            'name' => $data['name'],
            'number_of_subusers' =>  $data['number_of_subusers'],
            'number_of_industry' => $data['number_of_industry'],
            'number_of_transaction' => $data['number_of_transaction'],
            'amount' => $data['amount'],
            'description' => $data['description'],
        ]); 

      return $plan;

    }
}
