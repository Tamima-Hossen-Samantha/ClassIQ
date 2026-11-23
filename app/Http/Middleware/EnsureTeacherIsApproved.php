<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeacherIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('teacher');

        if (! $user || ! $user->is_approved) {
            return redirect()
                ->route('teacher.login')
                ->with('status', 'Your account is awaiting admin approval.');
        }

        return $next($request);
    }
}
