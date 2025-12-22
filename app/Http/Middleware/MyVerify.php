<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class MyVerify
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            !$request->user() ||
            ($request->user()->email !== null && !$request->user()->hasVerifiedEmail()) ||
            ($request->user()->phone !== null && !$request->user()->hasVerifiedPhone())
        ) {
            if ($request->user()->email !== null) {
                return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::route('verification.notice');
            }

            return $request->expectsJson()
                ? abort(403, 'Your phone number is not verified.')
                : Redirect::route('verification');
        }

        return $next($request);
    }
}
