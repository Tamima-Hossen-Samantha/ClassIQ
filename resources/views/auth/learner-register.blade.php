

@extends('layouts.landing_lays')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth_user.css') }}" />
    <script src="{{ asset('js/hero.js') }}"></script>
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>

<link rel="shortcut icon" href="images/Fevicon.png" />
@endpush

@section('title', 'Learner Sign Up | ClassIQ')

@section('content')
<section class="t-auth-hero">
  <div class="t-auth-inner">

    {{-- LEFT: illustration block --}}
   

    <div class="t-auth-illustration">
  <div class="t-auth-shade"></div>
  <img src="{{ asset('images/Studentauth.png') }}" alt="Learner illustration" class="t-auth-img">
</div>


    {{-- RIGHT: authentication form --}}
    <div class="t-auth-card">
      <h1 class="t-auth-title">Sign up</h1>
      <p class="t-auth-subtitle">Join ClassIQ for free as a</p>

      {{-- Role tabs --}}
      <div class="t-auth-tabs">
        <button type="button" class="t-auth-tab t-auth-tab--active">Learner</button>
        <a href="{{ route('register.teacher') }}" class="t-auth-tab">Teacher</a>
        
      </div>


@if ($errors->any())
    <div class="t-error-box" style="margin-bottom:12px;color:#b91c1c;font-size:0.9rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


     <form class="t-auth-form" method="POST" action="{{ route('learner.register.submit') }}">

    @csrf

    <div class="t-auth-row">
        <div class="t-field">
            <label>Full name</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-user"></i></span>
                <input type="text" name="name" class="t-input" placeholder="Full name" value="{{ old('name') }}" required>
            </div>
        </div>

        <div class="t-field">
            <label>Email</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-envelope"></i></span>
                <input type="email" name="email" class="t-input" placeholder="you@example.com" value="{{ old('email') }}" required>
            </div>
        </div>
    </div>

    <div class="t-auth-row">
        <div class="t-field">
            <label>Phone number</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-phone"></i></span>
                <input type="tel" name="phone" class="t-input" placeholder="+8801..." value="{{ old('phone') }}" required>
            </div>
        </div>

        <div class="t-field">
            <label>Class / Grade</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-book"></i></span>
                <input type="text" name="class_grade" class="t-input" placeholder="e.g. Class 7" value="{{ old('class_grade') }}">
            </div>
        </div>
    </div>

    <div class="t-auth-row">
        <div class="t-field">
            <label>Board / Curriculum</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-buildings"></i></span>
                <input type="text" name="board" class="t-input" placeholder="e.g. NCTB, English medium" value="{{ old('board') }}">
            </div>
        </div>

        <div class="t-field">
            <label>Preferred language</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-globe"></i></span>
                <input type="text" name="preferred_language" class="t-input" placeholder="e.g. Bangla & English" value="{{ old('preferred_language') }}">
            </div>
        </div>
    </div>

    {{-- Special needs yes/no --}}
    <div class="t-field t-field--full">
        <label>Do you have any special needs?</label>
        <div class="auth-radio-group">
            <label class="auth-radio-option">
                <input type="radio" name="special_need" value="yes" {{ old('special_need', 'no') === 'yes' ? 'checked' : '' }}>
                <span>Yes</span>
            </label>
            <label class="auth-radio-option">
                <input type="radio" name="special_need" value="no" {{ old('special_need', 'no') === 'no' ? 'checked' : '' }}>
                <span>No</span>
            </label>
        </div>
    </div>

    <div class="t-auth-row">
        <div class="t-field">
            <label>Password</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-lock-alt"></i></span>
                <input type="password" name="password" class="t-input" placeholder="Create a password" required>
            </div>
        </div>

        <div class="t-field">
            <label>Confirm password</label>
            <div class="t-input-wrap">
                <span class="t-input-icon"><i class="bx bx-lock-alt"></i></span>
                <input type="password" name="password_confirmation" class="t-input" placeholder="Repeat password" required>
            </div>
        </div>
    </div>

    <button type="submit" class="t-primary-btn">
        Create learner account
    </button>
</form>


      <p class="t-auth-bottom">
    Already have an account? <a href="{{ route('learner.login') }}">Log in</a>
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
