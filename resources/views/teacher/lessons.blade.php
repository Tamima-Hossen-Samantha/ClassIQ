@extends('layouts.teacher_lays')

@section('title', 'Lessons')
@section('page_title', 'Lessons')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="t-page">
        {{-- Header --}}
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Lessons</h1>
                <p class="mt-1 text-sm text-slate-500">
                    All lessons you’ve created across your courses.
                </p>
            </div>

            {{-- (Optional) You can add a global "New lesson" later if you build a topic picker --}}
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($lessons->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center text-sm text-slate-600">
                You have not created any lessons yet.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($lessons as $lesson)
                    @php
                        $topic   = $lesson->topic;
                        $chapter = $topic->chapter ?? null;
                        $course  = $chapter->course ?? null;

                        $statusBadge = $lesson->status === 'published'
                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                            : 'bg-slate-50 text-slate-700 ring-slate-100';

                        $approval = $lesson->approval_status;
                        $approvalBadge = $approval === 'approved'
                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                            : ($approval === 'rejected'
                                ? 'bg-red-50 text-red-700 ring-red-100'
                                : 'bg-amber-50 text-amber-700 ring-amber-100');
                    @endphp

                    <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <h2 class="text-sm font-semibold text-slate-900">
                                    {{ $lesson->title }}
                                </h2>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    {{ optional($course)->title ?? 'No course' }}
                                    @if ($chapter)
                                        · {{ $chapter->title }}
                                    @endif
                                    @if ($topic)
                                        · Topic: {{ $topic->title }}
                                    @endif
                                </p>
                                @if ($lesson->description)
                                    <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                        {{ $lesson->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col items-end gap-1">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $statusBadge }}">
                                    {{ ucfirst($lesson->status) }}
                                </span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $approvalBadge }}">
                                    {{ ucfirst($lesson->approval_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                            @if ($lesson->video_type)
                                <span>🎥 Video</span>
                            @endif
                            @if ($lesson->notes_path)
                                <span>📄 Notes</span>
                            @endif
                            @if ($lesson->worksheet_path)
                                <span>📝 Worksheet</span>
                            @endif
                            @if ($lesson->slides_path)
                                <span>📊 Slides</span>
                            @endif
                            @if ($lesson->image_paths)
                                <span>🖼 Images ({{ count($lesson->image_paths) }})</span>
                            @endif
                        </div>

                        <div class="mt-3 flex justify-end gap-2">
                            {{-- Later: add Edit / Preview / Delete actions --}}
                            {{-- Example edit link when you create lessonEdit route/view --}}
                            {{-- <a href="{{ route('teacher.lessons.edit', $lesson) }}" class="t-btn-secondary text-xs">
                                 Edit
                               </a> --}}
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $lessons->links() }}
            </div>
        @endif
    </div>
@endsection
