<?php

namespace App\Http\Middleware;

use App\Common\Utils\SystemTool;
use App\Exceptions\BaheException;
use Closure;

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
        $jwt = $request->header('JWT');
        if (empty($jwt)) {
            throw new BaheException(BaheException::JWT_NOT_EXIST);
        }

        try {
            SystemTool::getTokenInfo($jwt);
        } catch (\Exception $e) {
            throw new BaheException(BaheException::JWT_NOT_VALID);
        }

        return $next($request);
    }
}
