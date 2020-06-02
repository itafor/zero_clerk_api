<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
          if(authUser()){
          if ( authUser()->role->name == 'admin') {
      return $next($request);
    }

       return response()->json(['error'=>'Ooops!! You are not allowed to access this page'],403);
    }else{

       return response()->json(['error'=>'Ooops!! Not authenticated'],403);
    }
        }
    }

