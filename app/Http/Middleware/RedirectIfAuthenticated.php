<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as BaseRedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated extends BaseRedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // admin already logged in → go to admin dashboard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // learner already logged in → learner dashboard
                if ($guard === 'learner') {
                    return redirect()->route('learner.dashboard');
                }

                // default behaviour for others (web)
                return redirect($this->redirectTo($request) ?? '/');
            }
        }

        return $next($request);
    }
}
