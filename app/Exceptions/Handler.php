<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException;
use Illuminate\Session\TokenMismatchException;
use InvalidArgumentException;
use Psy\Readline\Hoa\FileException;
use ReflectionException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    
    // public function render($request, Throwable $exception)
    // {
    //     // Bad Request Http exception Error
    //     if ($exception instanceof BadRequestHttpException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 400,
    //             'message'   => 'Bad Request. Validation failed.',
    //         ], 400);
    //     }

    //     // Backed Enum Case Not Found exception Error
    //     if ($exception instanceof BackedEnumCaseNotFoundException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 400,
    //             'message'   => 'Invalid enum value provided.',
    //         ], 400);
    //     }

    //     // HTTP Response exception Error
    //     if ($exception instanceof HttpResponseException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 400,
    //             'message'   => 'Something went wrong, please try again later.',
    //         ], 400);
    //     }

    //     // Multiple Records Found exception Error
    //     if ($exception instanceof MultipleRecordsFoundException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 400,
    //             'message'   => 'Multiple records found when only one was expected.',
    //         ], 400);
    //     }

    //     // Invalid Argument exception Error
    //     if ($exception instanceof InvalidArgumentException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 400,
    //             'message'   => 'Invalid argument provided.',
    //         ], 400);
    //     }

    //     // Authentication exception Error
    //     if ($exception instanceof AuthenticationException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 401,
    //             'message'   => 'You are not authenticated.',
    //         ], 401);
    //     }

    //     // Authorization exception Error
    //     if ($exception instanceof AuthorizationException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 403,
    //             'message'   => 'You do not have permission to perform this action.',
    //         ], 403);
    //     }

    //     // Suspicious Operation exception Error
    //     if ($exception instanceof SuspiciousOperationException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 403,
    //             'message'   => 'Suspicious operation detected. The request has been denied.',
    //         ], 403);
    //     }

    //     // Not Found Http exception Error
    //     if ($exception instanceof NotFoundHttpException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 404,
    //             'message'   => 'Route not found.',
    //         ], 404);
    //     }

    //     // Not found exception Error
    //     if ($exception instanceof ModelNotFoundException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 404,
    //             'message'   => 'The requested resource was not found.',
    //         ], 404);
    //     }

    //     // Records Not Found exception Error
    //     if ($exception instanceof RecordsNotFoundException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 404,
    //             'message'   => 'The requested records were not found.',
    //         ], 404);
    //     }

    //     // Command Not Found exception Error
    //     if ($exception instanceof CommandNotFoundException) {
    //         return response()->json([
    //             'success' => false,
    //             'status' => 404,
    //             'message' => 'The command was not found.',
    //         ], 404);
    //     }

    //     // Method Not Allowed Http exception Error
    //     if ($exception instanceof MethodNotAllowedHttpException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 405,
    //             'message'   => 'HTTP Method not allowed for this route.',
    //         ], 405);
    //     }

    //     // Not Acceptable Http exception Error
    //     if ($exception instanceof NotAcceptableHttpException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 406,
    //             'message'   => 'The requested content type is not acceptable.',
    //         ], 406);
    //     }

    //     // Token Mismatch exception Error
    //     if ($exception instanceof TokenMismatchException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 419,
    //             'message'   => 'CSRF token mismatch. Please refresh the page and try again.',
    //         ], 419);
    //     }

    //     // Validation exception Error
    //     if ($exception instanceof ValidationException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 422,
    //             'message'   => 'Validation error.',
    //         ], 422);
    //     }

    //     // Throttle Requests exception Error
    //     if ($exception instanceof ThrottleRequestsException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 429,
    //             'message'   => 'Too many requests. Please try again later.',
    //         ], 429);
    //     }

    //     // Exception Error (Generic fallback for unexpected errors)
    //     if ($exception instanceof Exception) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 500,
    //             'message'   => 'An unexpected error occurred.',
    //         ], 500);
    //     }

    //     // Query exception Error (database query issues)
    //     if ($exception instanceof QueryException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 500,
    //             'message'   => 'There was an error executing the query.',
    //         ], 500);
    //     }

    //     // File exception Error (File upload issues)
    //     if ($exception instanceof FileException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 500,
    //             'message'   => 'File upload error.',
    //         ], 500);
    //     }

    //     // Reflection exception Error
    //     if ($exception instanceof ReflectionException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => 500,
    //             'message'   => 'Reflection error: Unable to reflect on the requested class or method.',
    //         ], 500);
    //     }

    //     // Http exception Error (catch-all for HTTP exceptions)
    //     if ($exception instanceof HttpException) {
    //         return response()->json([
    //             'success'   => false,
    //             'status'    => $exception->getStatusCode(),
    //             'message'   => $exception->getMessage(),
    //         ], $exception->getStatusCode());
    //     }

    //     // Default parent exception handler if none of the above match
    //     return parent::render($request, $exception);
    // }


}
