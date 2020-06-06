<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ParentUserController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api','parentUser']);
    }


   public function addSubUser(Request $request)
    {
    	$validatedData = $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_number'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|confirmed',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
         
        $validatedData['parent_id'] = authUser()->id;

    	$user = User::create($validatedData);

    	if($user){
    	return response(['message'=>'Sub user successfully added!!','user'=>$user],200);
    	}
    	return response(['error'=>'Registration failed!!'],403);
    }

       public function listsubUsers(Request $request){
    	$sub_users = User::where([
    		// ['id',authUser()->id],
    		['parent_id',authUser()->id]
    	])->with(['country','state'])->get();

    	if(count($sub_users) >=1 ){
    	return response()->json(['sub_users'=>$sub_users]);
    	}
    	return response(['error'=>'Sub users not found!!'],401);
    }
}
