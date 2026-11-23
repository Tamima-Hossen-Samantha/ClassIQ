<?php

namespace App\Http\Controllers\Learner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Learner;
use Illuminate\Support\Carbon;

class LearnerLoginController extends Controller
{
    /**
     * Show the learner login form.
     */
    public function showLoginForm()
    {
        return view('auth.user_login'); // your learner login blade
    }

    /**
     * Handle the learner login request.
     */



// ...

public function login(Request $request)
{
    // 1) Validate input
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2) Attempt login with learner guard
    if (Auth::guard('learner')->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // 3) Update last_login_at for this learner
        /** @var \App\Models\Learner $learner */
        $learner = Auth::guard('learner')->user();
        $learner->last_login_at = Carbon::now();
        $learner->save();

        // 4) Redirect to learner dashboard
        return redirect()->route('learner.dashboard');
    }

    // 5) Failed: back with error
    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ])->onlyInput('email');
}


    /**
     * Log the learner out.
     */
  public function logout(Request $request)
{
    
    Auth::guard('learner')->logout();

   
    $request->session()->invalidate();
    $request->session()->regenerateToken();

   
    return redirect()->route('Home');
}


}
