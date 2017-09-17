<?php

namespace App\Http\Middleware;

use App\Exceptions\BaheException;
use Closure;

class BeforeMiddleware
{

    /**
     * @param         $request
     * @param Closure $next
     * @return mixed
     * @throws BaheException
     */
    public function handle($request, Closure $next)
    {
        // 判断ua
        /*$ua = $request->header('User-Agent');
        if (!preg_match("/BH-MJProject/", $ua)) {
            throw new BaheException(BaheException::API_REQUEST_NOT_VALID);
        }*/

        return $next($request);
    }
}