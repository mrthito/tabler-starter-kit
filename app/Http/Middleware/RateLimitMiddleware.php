<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $key = 'default', int $limit = 5): Response
    {
        if (RateLimiter::tooManyAttempts($key . '|' . $request->ip(), $limit)) {
            return back()->with('error', __('Too many requests. Please try again later. Please try again in :seconds seconds.', ['seconds' => RateLimiter::availableIn($key . '|' . $request->ip())]));
        }

        RateLimiter::hit($key . '|' . $request->ip());

        return $next($request);
    }
}
