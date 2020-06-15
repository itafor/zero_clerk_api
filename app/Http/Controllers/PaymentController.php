<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    	 public function __construct()
    {
        $this->middleware('auth:api');
    }

      
  public function store(Request $request, $payment_id)
    {
    	$validatedData = $request->validate([
            'payment_type'=>'required',
            'balance'=>'required',
            'amount_paid'=>'required',
            'payment_method'=>'required',
        ]);

        $data = $request->all();


    	if($data['payment_method'] == 'Cash'){
    		if($data['amount_paid'] != $data['balance']){
    	return response(['error'=>'Amount paid must be equal to balance for cash payment'],403);
    		}
    	}

    	if($data['amount_paid'] <= 0 || $data['amount_paid'] > $data['balance'] || $data['balance'] == 0){
    	return response(['error'=>'Invalid payment details'],403);
    		}
       

        $data['payment_id'] = $payment_id;
        $data['payment_type'] = "App\\".$request->payment_type;

    	$payment = Payment::createNew($data);

    	if($payment){
    	return response(['message'=>'Payment recorded successfully!','payment'=>$payment],200);
    	}
    	return response(['error'=>'An attempt to record new payment failed!!'],403);
    }

    
}
