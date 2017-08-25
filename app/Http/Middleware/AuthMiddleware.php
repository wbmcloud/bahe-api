<?php

namespace App\Http\Middleware;

use App\Exceptions\BaheException;
use Closure;
use Firebase\JWT\JWT;

class AuthMiddleware
{
    /**
     * @param         $request
     * @param Closure $next
     * @return mixed
     * @throws BaheException
     */
    public function handle($request, Closure $next)
    {
        $jwt = $request->header('jwt');
        if (empty($jwt)) {
            throw new BaheException(BaheException::JWT_NOT_EXIST);
        }

        $key = env('JWT_SECRET');
        try {
            $decoded = JWT::decode($jwt, $key, array('HS256'));
        } catch (BaheException $e) {
            throw new BaheException(BaheException::JWT_NOT_VALID);
        }

        return $next($request);
    }
}
