<?php

namespace App\Http\Middleware;

use App\Common\Check\ParamsRules;
use Closure;

class ParamsValidator
{
    public static $messages = [
        'required'    => 'The :attribute field is required.',
        'digits'      => 'The :attribute field is not valid.',
        'integer'     => 'The :attribute field is not valid.',
        'date_format' => 'The :attribute field is not valid.',
        'string'      => 'The :attribute field is not valid.',
        'in'          => 'The :attribute must be one of the following types: :values',
        'size'        => 'The :attribute must be exactly :size.',
        'between'     => 'The :attribute must be between :min - :max.',
        'max'         => 'The :attribute must less than :max.',
        'numeric'     => 'The :attribute must be numeric.',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $resource_uri = preg_replace('/\?.*/', '', $request->getRequestUri());
        if (isset(ParamsRules::$rules[$resource_uri])) {
            app('validator')->validate($request->all(), ParamsRules::$rules[$resource_uri], self::$messages);
        }

        return $next($request);
    }
}
