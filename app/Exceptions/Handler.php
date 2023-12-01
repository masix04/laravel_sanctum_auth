<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Str;

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
            return $e;
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $renderResponse = null;
        if ($this->isHttpException($exception) && $exception->getStatusCode() == 404) {
            $renderResponse = response()->view('admin.error.' . '404', [], 404);
        }

        //---------------------------------FirstOrFail Model handling
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $renderResponse = returnResponse(
                404,
                new \stdClass(),
                'Content does not exist anymore or you do not have permission to view'
            );
        }
        //---------------------------------Validation fail Exception handling
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            $errorMessage = Arr::first($errors);
            $firstErrorMessage = $errorMessage[0];
            $renderResponse = returnResponse(
                422,
                [
                    'failed_rule' => Arr::first($exception->validator->failed())
                ],
                $firstErrorMessage
            );
        }
        //---------------------------------Api Not Found
        if (
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
            $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
           ) {
            $renderResponse = returnResponse(
                404,
                [
                    'exception' => $exception->getMessage()
                ],
                'Sorry, the page you are looking for could not be found. check your api and api type'
            );
        }

        return ($renderResponse)? $renderResponse: parent::render($request, $exception);
    }
}
