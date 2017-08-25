<?php

namespace App\Exceptions;

use App\Library\BContext;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $code    = $e->getCode();
        $message = $e->getMessage();
        if ($e instanceof NotFoundHttpException) {
            $code = BaheException::API_NOT_FOUND;
            $message = BaheException::$error_msg[BaheException::API_NOT_FOUND];
        }
        return $this->jsonException($code, $message);
    }

    protected function jsonException($code, $message)
    {
        return json_encode([
            'code'       => $code,
            'message'    => $message,
            'request_id' => BContext::getRequestId(),
            'data'       => (object)null,
        ]);
    }
}
