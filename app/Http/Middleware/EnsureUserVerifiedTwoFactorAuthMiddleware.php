<?php

namespace App\Http\Middleware;

use App\Traits\HasMfa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class EnsureUserVerifiedTwoFactorAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $guard = 'web'): Response
    {
        $user = Auth::guard($guard)->user();
        if (Session::get('two_factor_authenticated')) {
            return $next($request);
        }

        // check if the model has two factor authentication implemented and user has HasMfa trait
        if ($user->twoFactorAuthEnabled() && $user instanceof HasMfa) {
            return redirect()->route('admin.login.2fa')->with('error', __('Two factor authentication is not enabled.'));
        }

        return $next($request);
    }
}
