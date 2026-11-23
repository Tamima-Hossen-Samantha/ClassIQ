@extends('layouts.learner_lays')

@section('title', 'My classes | ClassIQ')
@section('page_title', 'My classes')

@section('content')
    <div class="l-page">
        {{-- Flash message --}}
        @if (session('status'))
            <div class="l-alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    My classes
                </h2>
                <p class="text-sm text-gray-500">
                    Let’s learn something fun today!
                </p>
            </div>
        </div>

        {{-- Join form panel --}}
        <div id="joinClassPanel" class="l-join-panel">
            <form method="POST"
                  action="{{ route('learner.classes.join') }}"
                  class="flex flex-wrap items-center gap-3">
                @csrf

                <div class="flex-1 min-w-[220px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Class code
                    </label>
                    <input
                        type="text"
                        name="join_code"
                        value="{{ old('join_code') }}"
                        class="l-input uppercase tracking-widest"
                        placeholder="ABC1234"
                        required
                    >

                    @error('join_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">
                            Enter the code your teacher shared for this course.
                        </p>
                    @enderror
                </div>

                <div class="pt-6">
                    <button type="submit" class="l-btn-primary">
                        Join
                    </button>
                </div>
            </form>
        </div>

        {{-- Joined classes --}}
        @if ($courses->isEmpty())
            <p class="text-sm text-gray-500 mt-2">
                You have not joined any classes yet. Use “Join new class” to get started.
            </p>
        @else
            <div class="l-classes-grid">
                @foreach ($courses as $course)
                    <div class="l-class-card">
                        <h3 class="l-class-title">
                            {{ $course->title }}
                        </h3>

                        <p class="l-class-meta">
                            Class {{ $course->class_level }} · {{ $course->subject }}
                        </p>

                        <p class="l-class-meta-small">
                            Teacher: {{ optional($course->teacher)->name }}
                        </p>

                        <p class="l-class-meta-small">
                            Joined:
                            @if ($course->pivot && $course->pivot->joined_at)
                                {{ $course->pivot->joined_at }}
                            @else
                                Not recorded
                            @endif
                        </p>

                        {{-- Open course: go to chapters/topics view --}}
                        <a href="{{ route('learner.course.chapters', $course) }}"
                           class="l-btn-primary mt-3 inline-flex items-center justify-center">
                            Open course
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
