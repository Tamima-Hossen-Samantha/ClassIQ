@extends('layouts.admin_lays')

@section('title', 'Teacher Profile | ClassIQ')
@section('page_title', 'Teacher Profile')
@section('page_subtitle', 'View and edit teacher details.')

@section('content')
    @if (session('status'))
        <div class="at-alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="at-card">
        <h2 class="at-heading">{{ $teacher->name }}</h2>
        <p class="at-meta">{{ $teacher->email }} · {{ $teacher->phone }}</p>
        <p class="at-meta-small">{{ $teacher->school_name }}</p>

        <p class="at-meta-small">
            Subjects: {{ $teacher->subjects_taught }}<br>
            Classes: {{ $teacher->classes_taught }}
        </p>

        @if ($teacher->nid_file_path)
            <p class="at-link-line">
                <a href="{{ asset('storage/'.$teacher->nid_file_path) }}" target="_blank" class="at-link">
                    View NID / Teacher ID
                </a>
            </p>
        @endif

        <p class="at-meta-small">
            Approved: {{ $teacher->is_approved ? 'Yes' : 'No' }}<br>
            Suspended: {{ $teacher->is_suspended ? 'Yes' : 'No' }}<br>
            Docs verified:
            {{ $teacher->documents_verified_at ? $teacher->documents_verified_at->format('Y-m-d H:i') : 'No' }}<br>
            Last login:
            {{ $teacher->last_login_at ? $teacher->last_login_at->format('Y-m-d H:i') : 'Never' }}
        </p>
    </div>

    {{-- Edit profile --}}
    {{-- Edit profile --}}
<div class="at-card at-mt-lg">
    <h3 class="at-heading">Edit profile</h3>

    <div class="at-two-col">
        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="at-form">
            @csrf
            @method('PUT')

            <div class="at-field">
                <label class="at-label">Name</label>
                <input type="text" name="name" class="at-input" value="{{ old('name', $teacher->name) }}">
            </div>

            <div class="at-field">
                <label class="at-label">Email</label>
                <input type="email" name="email" class="at-input" value="{{ old('email', $teacher->email) }}">
            </div>

            <div class="at-field">
                <label class="at-label">Phone</label>
                <input type="text" name="phone" class="at-input" value="{{ old('phone', $teacher->phone) }}">
            </div>

            <div class="at-field">
                <label class="at-label">School name</label>
                <input type="text" name="school_name" class="at-input" value="{{ old('school_name', $teacher->school_name) }}">
            </div>

            <div class="at-field">
                <label class="at-label">Subjects taught</label>
                <input type="text" name="subjects_taught" class="at-input" value="{{ old('subjects_taught', $teacher->subjects_taught) }}">
            </div>

            <div class="at-field">
                <label class="at-label">Classes taught</label>
                <input type="text" name="classes_taught" class="at-input" value="{{ old('classes_taught', $teacher->classes_taught) }}">
            </div>

            <button type="submit" class="at-btn at-btn-approve at-mt-md">
                Save changes
            </button>
        </form>

        <div class="at-form-side-info">
            <p class="at-meta-small">Use this form to fix small mistakes in teacher details.</p>
        </div>
    </div>
</div>


    
    
@endsection
