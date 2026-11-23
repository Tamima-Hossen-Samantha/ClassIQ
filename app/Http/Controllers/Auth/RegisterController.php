<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function showLearnerForm()
    {
        return view('auth.learner-register');
    }

    public function showTeacherForm()
    {
        return view('auth.teacher-register');
    }

    public function showParentForm()
    {
        return view('auth.parent-register');
    }
}
