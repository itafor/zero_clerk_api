<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
     public function buy_plan($plan_uuid = null)
    {
        $plan = SubscriptionPlan::where('uuid', $plan_uuid)->first();
        $plan_amount = str_replace(".", "", $plan->amount);
        return response()->json([
        	'plan'=>$plan,
        	'plan_amount'=>$plan_amount,
        ]);
    }

         /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
            $transaction = Transaction::create([
        	'uuid' => generateUUID(),
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
            'plan_id' => $request->plan_id,
            'status' => 'Pending',
            'channel' => 'Paystack',
            'reference' => generateUUID(),
            'amount' => $request->amount
        ]);
        $sub = Subscription::create([
        	'uuid' => generateUUID(),
            'user_id' => authUser()->parent_id == null ? authUser()->id : authUser()->parent_id,
            'transaction_id' => $transaction->uuid,
            'reference' => $transaction->reference,
            'plan_id' => $request->plan_id,
            'status' => 'Pending'
        ]);

            
     return Paystack::getAuthorizationUrl()->redirectNow();
    }

     /**
     * Obtain Paystack payment information
     * @return void
     */

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want

        if($paymentDetails['status'] == true){
            //dd('transaction was successful...');
            // transaction was successful...
            // please check other things like whether you already gave value for this ref
            // if the email matches the customer who owns the product etc
            // Give value

          $trans = Transaction::where('user_id',authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)->orderBy('created_at','desc')->first();
             
            if($trans){
            $reference = $trans->reference;
            $provider_reference = $paymentDetails['data']['reference'];
            $status = 'Success';

            $txn = Transaction::where('reference',$reference)->first();
            $txn = Transaction::find($txn->id);
            $txn->update([
                'status' => 'Successful',
                'provider_reference' => $provider_reference
            ]);
            //Find if user has any active subscription
            $active = Subscription::where('user_id', authUser()->parent_id == null ? authUser()->id : authUser()->parent_id)->where('status','Active')->get();
            if(!is_null($active)){
                foreach ($active as $act){
                    $sub_ = Subscription::find($act->id);
                    $sub_->update([
                        'status' => 'Revoked'
                    ]);
                }
            }
            $sub = Subscription::where('reference',$reference)->first();
            $sub = Subscription::find($sub->id);
            $sub->update([
                'status' => 'Active'
            ]);
           
            return response()->json(['success'=>'Congratulations! Your upgrade was successful!']);
        }
      }
    }

}
