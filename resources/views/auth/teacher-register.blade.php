@extends('layouts.landing_lays')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth_user.css') }}" />
    <script src="{{ asset('js/hero.js') }}"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="images/Fevicon.png" />
@endpush

@section('title', 'Teacher Sign Up | ClassIQ')

@section('content')
<section class="t-auth-hero">
  <div class="t-auth-inner">

    {{-- LEFT: illustration block --}}
    <div class="t-auth-illustration">
      <div class="t-auth-shade"></div>
      <img src="{{ asset('images/teacher_Auth.png') }}" alt="Teacher illustration" class="t-auth-img">
    </div>

    {{-- RIGHT: authentication form --}}
    <div class="t-auth-card">
      <h1 class="t-auth-title">Sign up</h1>
      <p class="t-auth-subtitle">Join ClassIQ for free as a</p>

      {{-- Role tabs --}}
      <div class="t-auth-tabs">
        <a href="{{ route('register.learner') }}" class="t-auth-tab">Learner</a>
        <button type="button" class="t-auth-tab t-auth-tab--active">Teacher</button>
      </div>

      {{-- Validation error (simple) --}}

      @if ($errors->any())
    <div class="t-error-box">
        {{ $errors->first() }}
    </div>
@endif

      {{-- Teacher registration form --}}
      <form class="t-auth-form"
            method="POST"
      action="{{ route('teacher.register.submit') }}"
      enctype="multipart/form-data">
    @csrf

        <div class="t-auth-row">
          <div class="t-field">
            <label>Full name</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-user"></i></span>
              <input type="text"
                     name="name"
                     class="t-input"
                     placeholder="Full name"
                     value="{{ old('name') }}"
                     required>
            </div>
          </div>

          <div class="t-field">
            <label>Email</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-envelope"></i></span>
              <input type="email"
                     name="email"
                     class="t-input"
                     placeholder="you@example.com"
                     value="{{ old('email') }}"
                     required>
            </div>
          </div>
        </div>

        <div class="t-auth-row">
          <div class="t-field">
            <label>Phone number</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-phone"></i></span>
              <input type="tel"
                     name="phone"
                     class="t-input"
                     placeholder="+8801..."
                     value="{{ old('phone') }}">
            </div>
          </div>

          <div class="t-field">
            <label>School name</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-buildings"></i></span>
              <input type="text"
                     name="school_name"
                     class="t-input"
                     placeholder="Your school"
                     value="{{ old('school_name') }}">
            </div>
          </div>
        </div>

        <div class="t-auth-row">
          <div class="t-field">
            <label>Subjects taught</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-book"></i></span>
              <input type="text"
                     name="subjects_taught"
                     class="t-input"
                     placeholder="e.g. Math, Science"
                     value="{{ old('subjects_taught') }}">
            </div>
          </div>

          <div class="t-field">
            <label>Classes taught</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-group"></i></span>
              <input type="text"
                     name="classes_taught"
                     class="t-input"
                     placeholder="e.g. Class 6–8"
                     value="{{ old('classes_taught') }}">
            </div>
          </div>
        </div>

        <div class="t-auth-row">
          <div class="t-field">
            <label>Password</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-lock-alt"></i></span>
              <input type="password"
                     name="password"
                     class="t-input"
                     placeholder="Create a password"
                     required>
            </div>
          </div>

          <div class="t-field">
            <label>Confirm password</label>
            <div class="t-input-wrap">
              <span class="t-input-icon"><i class="bx bx-lock-alt"></i></span>
              <input type="password"
                     name="password_confirmation"
                     class="t-input"
                     placeholder="Repeat password"
                     required>
            </div>
          </div>
        </div>

        <div class="t-field t-field--full">
          <label>Teacher ID / NID (upload)</label>
          <input type="file"
                 name="nid_file"
                 class="t-file-input"
                 accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <button type="submit" class="t-primary-btn">
          Create teacher account
        </button>
      </form>





      <p class="t-auth-bottom">
        Already have an account? <a href="{{ route('teacher.login') }}">Log in</a>
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
