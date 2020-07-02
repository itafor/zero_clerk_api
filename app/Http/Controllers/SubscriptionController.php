<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function uuidGeneration(){
    	$uuid = generateUUID();

    	return response()->json(['uuid'=>$uuid]);
    }
}
