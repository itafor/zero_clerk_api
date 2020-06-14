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

      
  public function store(Request $request, $payable_id)
    {
    	$validatedData = $request->validate([
            'payable_type'=>'required',
            'balance'=>'required',
            'amount_paid'=>'required',
            'payment_method'=>'required',
        ]);

        $data = $request->all();


    	if($data['payment_method'] == 'Cash'){
    		if($data['amount_paid'] != $data['balance']){
    	return response(['error'=>'Amount paid must be equal to total cost for cash payment'],403);
    		}
    	}

    	if($data['amount_paid'] <= 0 || $data['amount_paid'] > $data['balance'] || $data['balance'] == 0){
    	return response(['error'=>'Invalid payment details'],403);
    		}
       

        $data['payable_id'] = $payable_id;
        $data['payable_type'] = "App\\".$request->payable_type;

    	$payment = Payment::createNew($data);

    	if($payment){
    	return response(['message'=>'Payment recorded successfully!','payment'=>$payment],200);
    	}
    	return response(['error'=>'An attempt to record new payment failed!!'],403);
    }

    
}
