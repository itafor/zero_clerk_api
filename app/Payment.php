<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

     protected $fillable = [
        'user_id','payment_id','payment_type','payment_method','total_cost','amount_paid','balance'
    ];


    /**
     * Get all of the models that own comments.
     */
    public function paymentable()
    {
        return $this->morphTo();
    }

     public static function createNew($data){

   // $totalCost = $data['unit_cost'] * $data['quantity'];

        $payment = self::create([
            'payment_id' => $data['payable_id'],
            'payment_type' => $data['payable_type'],
            'payment_method' => $data['payment_method'],
            //'total_cost' => $data['total_cost'],
            'amount_paid' => $data['amount_paid'],
            'balance' =>  $data['balance'] - $data['amount_paid'],//$data['balance'] coming from payment item
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
        ]); 

        if($payment){
    		self::updatePaymentable($payment);
    	}

        
        return $payment;
    }

     public static function updatePaymentable($payable){
       	$get_payable = $payable->payment_type::where('id', $payable->payment_id)
       	  ->where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)
       	  ->first();

       		if($get_payable){
            $get_payable->balance = $payable->balance;
      		$get_payable->status = $payable->balance == 0 ? 'Paid' : 'Partly paid';
      		$get_payable->save();
      		}	
       }
    
}
