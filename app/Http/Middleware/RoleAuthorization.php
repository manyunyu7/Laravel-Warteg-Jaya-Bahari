<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = FacadesJWTAuth::parseToken()->authenticate();   
        if ($user && in_array($user->roles_id, $roles)) {
            return $next($request);
        }

        return $this->unauthorized();
    }

    private function unauthorized($message = null){
        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => $message ? $message : 'You are have no permission to access this resource',
        ], 401);
    }
}
