<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

	   public function __construct()
    {
        $this->middleware(['auth:api','admin']);
       
    }

    public function listUsers(Request $request){
    	$users = User::where('parent_id',null)->with(['subUsers','country','state'])->get();

    	if($users){
    		return response()->json(['users'=>$users],200);
    	}else{
    		return response()->json(['error'=>'No user found']);
    	}
    }

    public function listUser(Request $request, $userId){
    	$user = User::where([['parent_id',null],['id',$userId]])->with(['subUsers','country','state'])->first();

    	if($user){
    		return response()->json(['user'=>$user],200);
    	}else{
    		return response()->json(['error'=>'No user found']);
    	}
    }
}
