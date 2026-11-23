<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherRegisterController extends Controller
{
    // show the registration form
    public function showRegistrationForm()
    {
        return view('auth.teacher-register');
    }

    // handle POST /teacher/register
    public function register(Request $request)
    {
        // 1) validate input
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:teachers,email'],
         'phone'           => ['required', 'string', 'size:13', 'unique:teachers,phone'], 

            'school_name'     => ['required', 'string', 'max:255'],
            'subjects_taught' => ['required', 'string', 'max:255'],
            'classes_taught'  => ['required', 'string', 'max:255'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
            'nid_file'        => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // 2) handle NID file upload
        $nidPath = null;
        if ($request->hasFile('nid_file')) {
            $nidPath = $request->file('nid_file')->store('teachers_nid', 'public');
        }

        // 3) teacher approve by admin
        $teacher = Teacher::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'] ?? null,
            'school_name'     => $validated['school_name'] ?? null,
            'subjects_taught' => $validated['subjects_taught'] ?? null,
            'classes_taught'  => $validated['classes_taught'] ?? null,
            'nid_file_path'   => $nidPath,
            'password'        => Hash::make($validated['password']),
            'is_approved'     => false,
        ]);

        // 4) send email verification link
        event(new Registered($teacher)); // uses MustVerifyEmail

        // 5) log them in with teacher guard
        Auth::guard('teacher')->login($teacher);

        // 6) redirect to verify-email page
        return redirect()->route('teacher.verification.notice')
            ->with('status', 'Registered. Please verify your email.');
    }
}
