<?php

namespace App\Http\Middleware;

use App\Repositories\ApiKeyRepository;
use Closure;

class AuthApi
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
        $apiKeyRepo = new ApiKeyRepository();
        $apiKey     = (!empty($request->header('api-key'))) ? $request->header('api-key') : false;

        if (!$apiKey || !$apiKeyRepo->validate($apiKey)) {
            /* return response()->json([
                'code' => 401,
                'msg'  => 'API key is missing or wrong.'
            ]); */
        }

        return $next($request);
    }
}
