<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckUserToken
{
    public function handle(Request $request, Closure $next ,$roles)
    {
        $user = null;
        try{
            $roles = explode(',', $roles);
            $userRoles = ['Hospital', 'Admin', 'Paramedic'];
            foreach ($roles as $role)
            {
                if (in_array($role, $userRoles))
                {
                    return $next($request);
                }
            }
            $user = JWTAuth::parseToken()->authenticate();
            if($roles == $user->Type && $user->inService == 'Active')
            {
                return $next($request);
            }else{
                return response()->json(['message' => 'you are not allowed']);
            }
        }catch(\Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
            {
                return response()->json(['success' => false, 'msg' => 'Invalid Token']);
            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['success' => false, 'msg' => 'Expired Token']);
            }else{
                return response()->json(['success' => false, 'msg' => 'Not Found Token']);
            }
        }
        if(!$user)
        {
            return response()->json(['success' => false, 'msg' => 'Unauthenticated Login']);
        }
    }
}
