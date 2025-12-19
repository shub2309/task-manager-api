<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Exception;

class Handler extends Exception
{
    protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], Response::HTTP_UNAUTHORIZED);
    }

    abort(401);
}

}
