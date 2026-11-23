<?php

namespace App\Http\Controllers\Learner\Auth;

use App\Http\Controllers\Controller;
use App\Models\Learner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LearnerRegisterController extends Controller
{
    /**
     * Show the learner registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.learner-register');
    }

    /**
     * Handle the learner registration request.
     */
    public function register(Request $request)
    {
        // 1) Validate input
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:learners,email'],
            'phone'              => ['required', 'string', 'max:20', 'unique:learners,phone'],
            'class_grade'        => ['nullable', 'string', 'max:50'],
            'board'              => ['nullable', 'string', 'max:100'],
            'preferred_language' => ['nullable', 'string', 'max:100'],
            'special_need'       => ['required', 'in:yes,no'],
            'password'           => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2) Create learner
        Learner::create([
            'name'               => $validated['name'],
            'email'              => $validated['email'],
            'phone'              => $validated['phone'],
            'class_grade'        => $validated['class_grade'] ?? null,
            'board'              => $validated['board'] ?? null,
            'preferred_language' => $validated['preferred_language'] ?? null,
            'special_need'       => $validated['special_need'],
            'password'           => Hash::make($validated['password']),
        ]);

        // 3) Redirect to learner login (no auto-login)
        return redirect()
            ->route('learner.login')
            ->with('status', 'Thanks for signing up. Please log in.');
    }
}
