<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


   public function register(Request $request)
    {
    	$validatedData = $request->validate([
            'business_name'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_number'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|confirmed',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

    	$user = User::create($validatedData);

    	if($user){
    	return response(['message'=>'Successfully registered!!','user'=>$user],200);
    	}
    	return response(['error'=>'Registration failed!!'],403);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
         $user_profle = User::where('id',authUser()->id)->with(['country','state','subUsers'])->first();
            return response()->json(['User profile'=>$user_profle],200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


     public function updateUserProfile($userId, Request $request)
    {
      $userData = $request->validate([
            'business_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'area' => 'required',
            'address' => 'required',
        ]);

        DB::beginTransaction();

        try{
          $user = User::updateUser($userId,$request->all());
            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();
        return response()->json(['error'=>'Profile update failed'],403);
        }

        return response()->json(['success'=>'Profile updated successfully'],200);
    }

}