<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTRoleAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        try {
            $token_role = $this->auth->parseToken()->getClaim('role');
        } catch (JWTException $e) {
            return response()->json(['error' => 'unAuthenticated'], 401);
        }

        if ($token_role != $role) {
            return response()->json(['error' => 'unAuthenticated'], 401);
        }

        return $next($request);
    }
}
