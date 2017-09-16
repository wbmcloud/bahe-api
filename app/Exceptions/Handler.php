<?php

namespace App\Exceptions;

use App\Library\BContext;
use App\Library\BLogger;
use Exception;
use Firebase\JWT\ExpiredException;
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
        // parent::report($e);
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

        $log_info = [
            'code' => $code,
            'message' => $message,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'request' => $request->all(),
        ];
        if ($code > 2001000) {
            // 业务异常日志
            BLogger::warning($log_info);
        } else {
            BLogger::error($log_info);
        }

        if ($e instanceof NotFoundHttpException) {
            $code = BaheException::API_NOT_FOUND;
            $message = BaheException::$error_msg[BaheException::API_NOT_FOUND];
        } elseif ($e instanceof ExpiredException) {
            $code = BaheException::JWT_NOT_VALID;
            $message = BaheException::$error_msg[BaheException::JWT_NOT_VALID];
        } elseif ($e instanceof ValidationException) {
            $code = BaheException::API_ARGS_NOT_VALID;
            $message = BaheException::$error_msg[BaheException::API_ARGS_NOT_VALID];
        } elseif ($e->getCode() == 0) {
            $code = BaheException::API_UNKNOWN_ERROR;
            $message = BaheException::$error_msg[BaheException::API_UNKNOWN_ERROR];
        }

        return $this->jsonException($code, $message);
    }

    protected function jsonException($code, $message)
    {
        return response()->json([
            'code'       => $code,
            'message'    => $message,
            'request_id' => BContext::getRequestId(),
            'data'       => (object)null,
        ]);
    }
}
