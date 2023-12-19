<?php

declare(strict_types=1);

namespace Support\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ModelNotFoundException|NotFoundHttpException $e) {
            $model = match (true) {
                $e instanceof ModelNotFoundException => str($e->getModel())->basename()->ucsplit()->implode(' '),
                $e instanceof NotFoundHttpException  => str($e->getMessage())->between('[', ']')->afterLast('\\'),
            };

            $message = __($model . ' not found.');

            return response()->json(compact('message'), Response::HTTP_NOT_FOUND);
        });
    }
}
