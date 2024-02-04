<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if($guard != null)
        {
            auth()->shouldUse($guard);
            $token = $request->header('Authorization');
            $request->headers->set('Authorization', 'Bearer'.$token, true);
            try{
                $user = JWTAuth::parseToken()->authenticate();
                $request->attributes->add(['token' => $token]);
            }catch(TokenExpiredException $e){
                return  response()->json(['message' => 'Unauthenticated User', 'success' => false]);
            }catch(JWTException $e){
                return  response()->json(['message' => 'something went wrong AssignGuard', 'success' => false]);
            }
        }
        return $next($request);
    }
}

