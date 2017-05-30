<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Este cambio se realiza para manejar manualmente las excepciones por validación, mostrando los errores dentro de un JSON
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        // Este cambio se realiza para manejar manualmente las excepciones por id inexistente
        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return $this->errorResponse("No existe ese ID para {$model}", 404);
        }

        // Este cambio se realiza para manejar manualmente las excepciones por usuario no logeado
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        // Este cambio se realiza para manejar manualmente las excepciones por permisos
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse("No tienes permisos para esta acción", 403);
        }

        // Este cambio se realiza para manejar manualmente las excepciones por URL inexistentes
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("Esa URL no existe", 404);
        }

        // Este cambio se realiza para manejar manualmente las excepciones por métodos no definidos para URL eexistentes
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El método para esa URL no existe", 405);
        }

        // Este cambio se realiza para manejar manualmente otras excepciones de tipo HTTP no incluidas anteriomente
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // Este cambio se realiza para manejar manualmente excepciones de tipo relación existente entre recursos
        if ($exception instanceof QueryException) {
            //dd($exception);
            $code = $exception->errorInfo[1];

            if ($code == 1451) {
                return $this->errorResponse('Existe relación con otro recurso', 409);
            }
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        } else {
            return $this->errorResponse('Error interno', 500);
        }

    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse("No autenticado", 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);
    }
}
