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

        if (!$user) {
            return $next($request);
        }

        $sessionKey = "two_factor_authenticated_{$guard}";
        if (Session::get($sessionKey)) {
            return $next($request);
        }

        $usesHasMfa = in_array(HasMfa::class, class_uses_recursive($user));

        if ($usesHasMfa && method_exists($user, 'twoFactorAuthEnabled')) {
            /** @var \App\Traits\HasMfa $user */
            if ($user->twoFactorAuthEnabled()) {
                $routeName = $guard === 'admin' ? 'admin.login.2fa' : 'login.2fa';

                if (!$request->routeIs($routeName)) {
                    return redirect()->route($routeName)->with('error', __('Please verify your two factor authentication code.'));
                }
            }
        }

        return $next($request);
    }
}
