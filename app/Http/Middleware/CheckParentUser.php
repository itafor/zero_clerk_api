<?php

namespace App\Http\Middleware;

use Closure;

class CheckParentUser
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
        if(authUser()->parent_id == null){
        return $next($request);
        }

       return response()->json(['error'=>'Ooops!! You are not allowed to access this page'],403);
    }
}
