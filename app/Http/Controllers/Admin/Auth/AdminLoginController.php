<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // GET /admin/login/{token}
    public function showLoginForm(string $token)
    {
        // Always start fresh: log out any existing admin session
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Find admin by token
        $admin = Admin::where('login_token', $token)->first();

        if (! $admin) {
            // Invalid or used token → send to Home
            return redirect()->route('Home');
        }

        // Show login form and prefill username if you want
        return view('auth.login_admin', [
            'token'    => $token,
            'username' => $admin->username,
        ]);
    }

    // POST /admin/login/{token}
    public function login(Request $request, string $token)
    {
        $admin = Admin::where('login_token', $token)->first();

        if (! $admin) {
            return redirect()->route('Home');
        }

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // one-time: kill token so URL can’t be reused
            $admin->update(['login_token' => null]);

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['username' => 'Invalid username or password.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('Home');
    }
}
