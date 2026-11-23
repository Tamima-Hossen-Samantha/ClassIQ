@extends('layouts.teacher_lays')

@section('title', 'Teacher dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Quick overview of your teaching activity.')
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="t-page space-y-6">
        {{-- Top stats cards --}}
        <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total courses</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalCourses }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Students reached</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalStudents }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lessons created</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $totalLessons }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Courses status</p>
                <p class="mt-2 text-sm text-slate-800">
                    <span class="font-semibold">{{ $publishedCourses }}</span> published,
                    <span class="font-semibold">{{ $draftCourses }}</span> draft
                </p>
            </div>
        </div>

        {{-- Recent lessons + quick actions --}}
        <div class="grid gap-4 md:grid-cols-3">
            {{-- Recent lessons --}}
            <div class="md:col-span-2 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900">Recent lessons</h2>
                    <a href="{{ route('teacher.lessons.index') }}"
                       class="text-xs font-medium text-blue-600 hover:underline">
                        View all
                    </a>
                </div>

                @if ($recentLessons->isEmpty())
                    <p class="mt-3 text-xs text-slate-500">
                        You haven’t created any lessons yet.
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
                                <span class="whitespace-nowrap text-[11px] text-slate-400">
                                    {{ $lesson->created_at->diffForHumans() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Quick actions --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Quick actions</h2>
                <ul class="mt-3 space-y-2 text-xs">
                    <li>
                        <a href="{{ route('teacher.courses.create') }}"
                           class="inline-flex w-full items-center justify-between rounded-md border border-slate-200 bg-slate-50 px-3 py-2 hover:bg-slate-100">
                            <span>Create new course</span>
                            <i class="bx bx-plus text-base"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.courses') }}"
                           class="inline-flex w-full items-center justify-between rounded-md border border-slate-200 bg-slate-50 px-3 py-2 hover:bg-slate-100">
                            <span>Manage my courses</span>
                            <i class="bx bx-edit-alt text-base"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.lessons.index') }}"
                           class="inline-flex w-full items-center justify-between rounded-md border border-slate-200 bg-slate-50 px-3 py-2 hover:bg-slate-100">
                            <span>View all lessons</span>
                            <i class="bx bx-book-open text-base"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('teacher.students') }}"
                           class="inline-flex w-full items-center justify-between rounded-md border border-slate-200 bg-slate-50 px-3 py-2 hover:bg-slate-100">
                            <span>View my students</span>
                            <i class="bx bx-group text-base"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Upcoming tasks (open doubts) + notifications / alerts --}}
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Upcoming tasks: unanswered doubts --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Upcoming tasks</h2>
                <p class="mt-1 text-xs text-slate-500">
                    Open questions from your students.
                </p>

                @if ($openDoubts->isEmpty())
                    <p class="mt-3 text-xs text-slate-500">No open questions right now.</p>
                @else
                    <ul class="mt-3 space-y-2 text-xs">
                        @foreach ($openDoubts as $doubt)
                            @php
                                $lesson = $doubt->lesson;
                                $course = optional($lesson->topic->chapter)->course;
                            @endphp
                            <li class="rounded-md bg-slate-50 px-3 py-2">
                                <p class="text-[11px] text-slate-500">
                                    {{ $doubt->learner->name }} · {{ $course->title ?? 'Course' }} · {{ $lesson->title }}
                                </p>
                                <p class="mt-1 text-sm text-slate-900 line-clamp-2">
                                    {{ $doubt->question }}
                                </p>
                                <p class="mt-1 text-[11px] text-slate-400">
                                    {{ $doubt->created_at->diffForHumans() }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Student performance alerts (placeholder) --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Student performance alerts</h2>
                @if ($performanceAlerts->isEmpty())
                    <p class="mt-2 text-xs text-slate-500">
                        No alerts yet. Later this will show students who need extra help based on quiz results.
                    </p>
                @else
                    <ul class="mt-2 space-y-2 text-xs">
                        @foreach ($performanceAlerts as $alert)
                            <li class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $alert->title }}</p>
                                    <p class="text-slate-500">{{ $alert->description }}</p>
                                </div>
                                <span class="whitespace-nowrap text-slate-400">
                                    {{ $alert->created_at->diffForHumans() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
