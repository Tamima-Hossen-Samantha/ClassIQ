@extends('layouts.learner_lays')

@section('title', 'Dashboard')
@section('page_title', 'Welcome back')
@section('page_subtitle', 'Here is your learning overview for today.')
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <div class="l-page space-y-6">
        {{-- Welcome + today’s suggestion --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">
                    Hello, {{ $learner->name }} 👋
                </h2>

                @if ($suggestedCourse)
                    <p class="mt-1 text-sm text-slate-600">
                        Today’s suggestion: continue
                        <span class="font-semibold">{{ $suggestedCourse->title }}</span>.
                    </p>

                    @if ($suggestedLesson)
                        <div class="mt-3 flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
                            <div>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $suggestedLesson->title }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $suggestedCourse->title }}
                                    @if ($suggestedLesson->topic?->chapter)
                                        · {{ $suggestedLesson->topic->chapter->title }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('learner.lessons.show', $suggestedLesson) }}"
                               class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                Start lesson
                            </a>
                        </div>
                    @else
                        <p class="mt-3 text-xs text-slate-500">
                            This course has no lessons yet.
                        </p>
                    @endif
                @else
                    <p class="mt-1 text-sm text-slate-600">
                        You haven’t joined any class yet. Ask your teacher for a class code to get started.
                    </p>
                @endif
            </div>

            {{-- Daily streak --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Daily streak</h2>
                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ $currentStreak }}<span class="text-base text-slate-500"> days</span>
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Best streak: {{ $bestStreak }} day(s)
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Learning every day helps you remember better. Try not to break the chain.
                </p>
            </div>
        </div>

        {{-- Progress + pending quizzes --}}
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Progress overview --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Progress overview</h2>
                <p class="mt-2 text-sm text-slate-700">
                    Courses joined:
                    <span class="font-semibold">{{ $courseProgress['joined'] }}</span><br>
                    Courses completed:
                    <span class="font-semibold">{{ $courseProgress['completed'] }}</span>
                </p>
                <p class="mt-2 text-xs text-slate-500">
                    Later this card can show detailed course completion percentage and lesson progress.
                </p>
            </div>

            {{-- Pending quizzes --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Pending quizzes</h2>
                @if ($pendingQuizzesCount === 0)
                    <p class="mt-2 text-xs text-slate-500">
                        No quizzes are waiting right now.
                    </p>
                @else
                    <p class="mt-2 text-xs text-slate-500">
                        You have <span class="font-semibold">{{ $pendingQuizzesCount }}</span> quiz(es) to complete.
                    </p>
                    {{-- Later: list each quiz with a Start button --}}
                @endif
            </div>
        </div>

        {{-- Recently viewed / recent lessons --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Recently viewed lessons</h2>
            </div>

            @if ($recentLessons->isEmpty())
                <p class="mt-2 text-xs text-slate-500">
                    When you start learning, your recent lessons will appear here.
                </p>
            @else
                <ul class="mt-3 space-y-2 text-xs">
                    @foreach ($recentLessons as $lesson)
                        @php
                            $topic   = $lesson->topic;
                            $chapter = $topic->chapter ?? null;
                            $course  = $chapter->course ?? null;
                        @endphp
                        <li class="flex items-start justify-between gap-3 rounded-md bg-slate-50 px-3 py-2">
                            <div>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $lesson->title }}
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    {{ $course->title ?? 'Course' }}
                                    @if ($chapter) · {{ $chapter->title }} @endif
                                    @if ($topic) · Topic: {{ $topic->title }} @endif
                                </p>
                            </div>
                            <a href="{{ route('learner.lessons.show', $lesson) }}"
                               class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-100">
                                Resume
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
