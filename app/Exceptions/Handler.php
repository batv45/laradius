<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laracasts\Flash\FlashServiceProvider;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConnectException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
    }
    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object|\Symfony\Component\HttpFoundation\Response|Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (!app()->environment('local') && in_array($response->status(), [500, 503, 404])) {

//            return Inertia::render('Errors/Error', ['status' => $response->status()])
//                ->toResponse($request)
//                ->setStatusCode($response->status());
        } else if ($response->status() === 419) {
            return back()->with([
                'message' => 'The page expired, please try again.',
            ]);
        }else if ($response->status() === 403 && $request->inertia()) {
//            alert()->error('Yetkisiz işlem!','Bu işlem için yetkiniz yok.');  // SweetAlert bildirisi
            flash('Bu işlem için yetkiniz yok.')->error();
            return back();
        }else if ($response->status() === 429 && $request->inertia()) {
//            alert()->error('Yetkisiz işlem!','Bu işlem için yetkiniz yok.');  // SweetAlert bildirisi
            flash('Bağlantı istek sınırını aştınız.')->important()->error();
            return back();
        }

        return $response;
    }
}
