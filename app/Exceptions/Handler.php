<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Repositories\EmailRepository;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
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
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            try {
                if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
                    return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Page not found']);
                }

                if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
                    return response()->json(['code' => 404, 'status' => 'error', 'message' => 'Method not found']);
                }

                if (env('APP_ENV') != '' && env('APP_ENV') != 'local') {
                    if ($exception instanceof \Exception) {
                        $e = FlattenException::create($exception);
                        $handler = new HtmlErrorRenderer(true);
                        $css = $handler->getStylesheet();
                        $content = $handler->getBody($e);

                        $email = new EmailRepository();
                        $email->sendException($css, $content);
                    } elseif ($exception instanceof \Error) {
                        $handler = new HtmlErrorRenderer(true);
                        $css     = $handler->getStylesheet();
                        $content = $exception->getTraceAsString();

                        $email = new EmailRepository();
                        $email->sendException($css, $content);
                    }
                }
            } catch (Throwable $ex) {
                Log::error($ex);
            }
        }

        parent::report($exception);
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
        return parent::render($request, $exception);
    }
}
