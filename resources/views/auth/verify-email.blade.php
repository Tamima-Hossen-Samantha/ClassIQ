@extends('layouts.landing_lays')

@section('title', 'Verify Email | ClassIQ')

@push('styles')
<style>
    .verify-wrapper {
        min-height: calc(100vh - 200px); /* leave space for header/footer */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        background: #f5f5f3;
    }

    .verify-card {
        max-width: 520px;
        width: 100%;
        background: #ffffff;
        border-radius: 32px;
        padding: 32px 32px 28px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18);
        text-align: center;
    }

    .verify-illustration {
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        border-radius: 999px;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .verify-illustration img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .verify-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .verify-text {
        font-size: 0.98rem;
        color: #4b5563;
        margin-bottom: 18px;
    }

    .verify-status {
        font-size: 0.9rem;
        color: #059669;
        margin-bottom: 16px;
    }

    .verify-note {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 10px;
    }

    .verify-btn {
        width: 100%;
        height: 46px;
        border-radius: 999px;
        border: none;
        background: #020617;
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.35);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .verify-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.4);
    }
</style>
@endpush

@section('content')
<div class="verify-wrapper">
    <div class="verify-card">

        {{-- illustration at top --}}
        <div class="verify-illustration">
            <img src="{{ asset('images/Fevicon.png') }}" alt="Email verification">
        </div>

        {{-- text --}}
        <h1 class="verify-title">Verify your email</h1>

        <p class="verify-text">
            A verification link has been sent to your teacher email address.
        </p>

        @if (session('status'))
            <p class="verify-status">{{ session('status') }}</p>
        @endif

        {{-- button --}}
        <form method="POST" action="{{ route('teacher.verification.send') }}">
            @csrf
            <button type="submit" class="verify-btn">
                Resend verification email
            </button>
        </form>

        <p class="verify-note">
            Didn’t get the email? Check your spam folder or try resending after a minute.
        </p>
    </div>
</div>
@endsection
