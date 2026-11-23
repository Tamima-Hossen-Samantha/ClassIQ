<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.teacher_login'); // adjust path if different
    }

    public function login(Request $request)
    {
        // 1) validate input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2) find teacher by email
        $teacher = Teacher::where('email', $credentials['email'])->first();

        // invalid email or password
        if (! $teacher || ! Hash::check($credentials['password'], $teacher->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // 3) not approved yet
        if (! $teacher->is_approved) {
            return back()->withErrors([
                'email' => 'Please wait, we are reviewing your information.',
            ])->onlyInput('email');
        }

        // 4) approved: log in via teacher guard
        Auth::guard('teacher')->login($teacher);
        $request->session()->regenerate();

        $teacher->forceFill([
        'last_login_at' => now(),
    ])->save();

        return redirect()->route('teacher.dashboard');
    }


public function logout(Request $request)
{
    // 1) Log out from teacher guard
    Auth::guard('teacher')->logout();

    // 2) Invalidate session and token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // 3) Redirect to landing page
    return redirect()->route('Home');
}


}
