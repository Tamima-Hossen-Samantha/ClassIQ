@extends('layouts.landing_lays')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" />
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
@endpush

@section('title', 'Log in | ClassIQ')

@section('content')
<section class="t-auth-hero">
    <div class="t-auth-inner">

        {{-- LEFT: illustration --}}
        <div class="t-auth-illustration">
            <div class="t-auth-shade"></div>
            <img src="{{ asset('images/teacher_Auth.png') }}" alt="Login illustration" class="t-auth-img">
        </div>

        {{-- RIGHT: tabs + login card --}}
        <div class="t-auth-card">
            <h1 style="
                color: #1b1b1b;
                font-size: 2rem;
                font-weight: 800;
                text-transform: uppercase;
            ">Log in</h1>

            <p style="
                color: #1f1919;
                font-size: 1.5rem;
                font-weight: 500;
            ">Welcome back to ClassIQ</p>
<!-- role based -->
            <div class="te-auth-tabs">
    <a href="{{ route('learner.login') }}" class="te-auth-tab te-auth-tab--active">
        Learner
    </a>
    <a href="{{ route('teacher.login') }}" class="t-auth-tab">
        Teacher
    </a>
</div>

@if (session('status'))
    <div class="t-status-box" style="margin-bottom:12px;color:#16a34a;font-size:0.9rem;">
        {{ session('status') }}
    </div>
@endif


{{-- error message --}}
@if ($errors->any())
    <div class="t-error-box" style="margin-bottom:12px;color:#b91c1c;font-size:0.9rem;">
        {{ $errors->first() }}
    </div>
@endif

            
            {{-- learner login form --}}
<form class="t-auth-form" method="POST" action="{{ route('learner.login.submit') }}">
    @csrf

    {{-- Email --}}
    <div class="t-auth-row">
        <div class="t-field t-field--full">
            <label>Email</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-envelope"></i></span>
                <input type="email" name="email" class="t-input" placeholder="you@example.com" required>
            </div>
        </div>
    </div>

    {{-- Password --}}
    <div class="t-auth-row">
        <div class="t-field t-field--full">
            <label>Password</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-lock-alt"></i></span>
                <input type="password" name="password" class="t-input" placeholder="Your password" required>
            </div>
        </div>
    </div>

    <button type="submit" class="t-primary-btn">
        Log in
    </button>
</form>


            <p class="t-auth-bottom">
    Don’t have an account?
    <a href="{{ route('learner.register') }}">Sign up</a>
</p>


            <div class="t-divider"><span>or</span></div>

            <button type="button" class="t-google-btn">
                <i class="bx bxl-google"></i>
                <span>Continue with Google</span>
            </button>
        </div>

    </div>
</section>
@endsection
