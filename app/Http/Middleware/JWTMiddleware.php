<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
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
        try {

            $user = JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                return response()->json([
                    'message' => 'Token is Invalid',
                    'status' => false
                ], 422);

            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {

                return response()->json([
                    'message' => 'Token is Expired',
                     'status' => false
                ], 401);

            } else {

                return response()->json([
                    'message' => 'Authorization Token not found',
                     'status' => false
                ], 403);

            }
        }

        return $next($request);
    }
}
